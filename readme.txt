web pm-studio
-------------



* použité technologie: *
XAMPP 1.7.3
Apache 2.2
PHP 5.3.1
MySQL 5.1.41
Nette Framework 2 (@todo : hash a datum sem)
dibi 1.5rc
Texy 2.1
GIT 1.7.0.2 msgit
HTML5
CSS3
Less CSS 0.2.0 lessphp
Poedit 1.4.6
NetBeans IDE 7.0 Beta 2 (PHP Nette Framework plugin, Git plugin)
mspaint 5.1
Firefox 4.0 (Firebug 1.7.0, FireQuery 0.9, FireLogger 0.9, HTML validator 0.9.0.4)



* požadavky na hosting: *

PHP 5.3 nebo vyšší s nastavením: @todo : vypsat potřebné nastavení
MySQL případně jiná databáze podporována dibi
pro rychlý test vhodnosti hostingu na něm stačí spustit drobný php script: https://github.com/nette/tools/tree/master/Requirements-Checker , který je standardní součástí distribuce nette frameworku.



* zprovoznění na hostingu: *

Struktura složek se je mírně upravena, tak že všechny soubory jsou rozděleny do 2 hlavních složek 'www' a 'pm' (každá dál obsahuje své podložky).
Z čehož ve složce www jsou všechny soubory, které mohou být dostupné z webu návštěvníkem a složka pm potom obsahuje zdrojové kódy projektu a další soubory, které musí být návštěvníkovi nedostupné.
Ostatní složky není pro funkci webu potřeba nikam nahrávat, jde o soubory vývojových nástroju (.git, nbproject), zálohy databáze, a složka pro unit a jednotkové testy

Na hostingu je třeba nastavit práva složek /pm/temp a /www/webtemp na 777 (unix) protože do těchto složek se ukládají cache, a další dočasné soubory. Stejně tak složku /pm/log , kam se ukládají případné chyby, varování a vijímky místo toho aby se vypisovaly uživateli aplikace.



* První použití: *

Po přihlášení do backend sekce webu (/auth) se v (pravém) bočním slupci zobrazí několik možností úprav. Pro zavedení webu je prvně důležitá položka 'upravit menu' (/menu) kde se vybere obsazení menu buď předdefinovanými sekcemi, nebo vlastní sekcí.
Vlastní sekce potom dokončíte v sekci 'upravit obsah obsahových sekcí' (/content). Pro formátování obsahu je zde použito texy! syntax .
Globální nastavení webu je k nalezení v sekci 'upravit nastavení webu' (/settings).



* stručně - základní vlastnosti: *

SEO URI jsou kanonické v českém jazyce, ale mají své funkční anglické alternativy. Například adresa /novinky/pridat má stejný obsah jako /news/add, ale druhá jmenovaná bude viditelně přesměrována na první.
Přihlášenému uživateli s právy administrátora je zobrazen debug bar nette, ten má navíc hodně přídavků, což aplikaci mírně spomaluje.
Všechny soubory jsou v kódování UTF-8 s UNIXovým ukončováním řádků.



* testování: *

Ručně testováno zobrazení a funkce na prohlížečích @todo: otestovat

@todo: udělat alespoň pár ukázkových unit testů.



ukázka na http://sl.edov.at
zdrojové kódy https://github.com/iiic/pm-studio

poslední změna 27. 03. 2011
