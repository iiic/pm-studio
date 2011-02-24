<?php

use Nette\Object,
	Nette\Security\AuthenticationException,
	Nette\Environment;


/**
 * Users authenticator.
 */
class UsersModel extends Object implements Nette\Security\IAuthenticator
{

	/**
	 * Performs an authentication
	 * @param  array
	 * @return IIdentity
	 * @throws AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		//list($username, $password, $admins, $token) = $credentials;// nejde použít protože nemusí přijít všechny prvky

		if(isset($credentials[0])) { $username = $credentials[0]; }
		if(isset($credentials[1])) { $password = $credentials[1]; }
		if(isset($credentials[2])) { $admins = $credentials[2]; }// @todo : proměnná admins by se sem měla dostat jiným způsobem... tohle dělá problém s rozšířením pro debugbar userPanel
		if(isset($credentials[3])) { $token = $credentials[3]; }

		if(isset($password)) {
			$row = dibi::select('*')
				->from('pm_users')
				->where('username=%s', $username)
				->fetch();
		} else { //jde o OpenID
			$admins = $username[1];
			$row = dibi::select('*')
				->from('pm_users')
				->innerJoin('users_openid')
				->on('pm_users.id = users_openid.user_id')
				->where('openid=%s', $username[0])
				->fetch();
			//$username = $row->username;// asi
		}

		$admins = explode('|', $admins);// rozdělení do pole podle oddělovače

		if(in_array($row['id'], $admins)) {// pokud se $row['id'] nachází v poli $admins
			$roles = 'administrator';
		} else {
			$roles = 'registred';
		}

		if(!$row) {
			throw new AuthenticationException('Uživatel '.$username.' nenalezen.', self::IDENTITY_NOT_FOUND);
		}

		if(isset($password)) {
			if($token) {
				if( (hash('sha256', Environment::getConfig('security')->shortSalt1.$token.'ixD'.$this->decryptPasswd($row->password, $username).Environment::getConfig('security')->shortSalt2) !== $password) && (hash('sha256', Environment::getConfig('security')->shortSalt1.$token.'ixD'.$this->decryptPasswd($row->password, $username, false).Environment::getConfig('security')->shortSalt2) !== $password) ) {
					throw new AuthenticationException("Nesprávné heslo.", self::INVALID_CREDENTIAL);
				}
			} else {
				if( ($this->decryptPasswd($row->password, $username) !== $password) && ($this->decryptPasswd($row->password, $username, false) !== $password) ) {
					throw new AuthenticationException("Nesprávné heslo.", self::INVALID_CREDENTIAL);
				}
			}
		}

		if( (isset($row->user_id)) && ($row->user_id > 0) ) {// pokud je nějaké user_id, znamená to, že se uživatel přihlašoval pomocí OpenID
			$row->id = $row->user_id;
		}
		unset($row->user_id);
		unset($row->password);
		unset($row->openid);
		return new Nette\Security\Identity($row->id, $roles, $row);
	}



	public function registerNew(array $data)
	{
		$data['date'] = time();
		$data['password'] = $this->encryptPasswd($data['password'], $data['username']);
		dibi::query('INSERT INTO [pm_users] %v', $data);
		return dibi::InsertId();
	}



	public function registerOpenId(array $data)
	{
		$data['date'] = time();
		$identity = $data['identity'];
		unset($data['identity']);
		try {
			dibi::query('INSERT IGNORE INTO [pm_users] %v', $data);
			$id = dibi::InsertId();
		} catch(DibiException $e) {
			return false;// toto uživatelské jméno nebo email už máme, takže registrovat
			//throw $e; //vyhodit vyjímku
		}
		if(isset($id)) {
			dibi::query('INSERT INTO [users_openid] %v', array('user_id' => $id, 'openid' => $identity, 'date' => $data['date']));
		}
		return dibi::InsertId();
	}



	public function getUserList()
	{
		return dibi::select('*')->from('pm_users')->orderBy('id')->fetchPairs('id', 'username');
	}



	private function encryptPasswd($password, $username)
	{
		if(strlen($password) > 150) {// pokud je heslo delší než 150 znaků
			$password = substr($password, 0, 150);// oříznu ho právě na 150 znaků
		} else {// jinak je heslo kratší
			if(strlen($password) < 80) {
				$password = $password.$username; // propojím heslo se jménem
			}
			if(strlen($password) < 80) {// pokud je heslo stále slabé, zvojím ho
				$password = $password.$password;
			}
		}
		$secret_key = Environment::getConfig('security')->secretKey;
		$secret_key = substr($secret_key, 0, strlen($password));// tohle je možná zbytečné
		return base64_encode($secret_key ^ $password);// jen doufám že se 160 znakový řetězec nenatáhne na více než 250 znaků
	}



	private function decryptPasswd($password, $username, $first_try = true)
	{
		$secret_key = Environment::getConfig('security')->secretKey;
		$secret_key = substr($secret_key, 0, strlen($password));// tohle je možná zbytečné
		$return = $secret_key ^ base64_decode($password);
		if(strlen($return)%2 == 0) {// liché jde kratším zápisem : if(strlen($return)%2)...
			$begin = substr($return, strlen($return)/2);
			if($begin == substr($return, 0, strlen($return)/2)) {
				$return = $begin;
			}
		}
		if(strlen($return)-strlen($username) < 80) {
			if( (substr($return, -strlen($username)) == $username) && $first_try ) {// pokud se v průběhu dojdeme až sem s tím že konec hesla bude stejný jako jako celé uživatelské jméno může nastat chyba, tu pak bude řešit druhý pokus s tím, že se nebude už stříhat
				$return = substr($return, 0, -strlen($username));
			}
		}
		return $return;
	}

}
