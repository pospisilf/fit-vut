# Tvorba uživatelských rozhraní - Projekt
## CarInfo - aplikace na správu informací o vozidle -  xpospi0f


### Autoři
- Filip Pospíšil <xpospi0f@stud.fit.vutbr.cz>
- Tomáš Lisický <xlisic01@stud.fit.vutbr.cz>
- Ondřej Bradáč <xbrada19@stud.fit.vutbr.cz>


### Dokumentace
- Technická zpráva projektu: `./doc/xpospi0f_tz.pdf`.

### Požadavky
- [php](https://www.php.net/) ve verzi 7.4.3 nebo novější
- [symfony](https://symfony.com/) ve verzi 5.3.12 nebo novější
- [MySQL](https://www.mysql.com/) ve verzi 8.0.27 nebo novější
- [Composer](https://getcomposer.org/) ve verzi 1.10.1 nebo novější

### Instalace
1. Pro spuštění realizace projektu je nejprve nutné vytvořit nový, prázdný symfony projekt. Toho docílíme pomocí následujícího příkazu: 
````shell 
    $ composer create-project symfony/website-skeleton CarInfo
````

1. Po vytvoření adrsářové struktury se přepneme do složky s nově vytvořeným projektem `CarInfo`
````shell 
    $ cd CarInfo
````

1. Do daného adresáře vložíme celý obsah složky `src` z odevzdaného archivu. Případné existující soubory přepíšeme.

2. Soubor `.env` v kořenové složce projektu obsahuje informace pro projení s databází. Pro podrobnější informace k nastavení databáze je třeba nahlédnout do dokumentace Symfony. Pro užití MySQL stačí následující:
````php 
#obecně
    DATABASE_URL="mysql://user:password@url:port/db_name?serverVersion=5.7"
#konkrétně pro uživatele admin s heslem 123456789 běžícím na localhostu s defaultním portem a strukturou carinfo
    DATABASE_URL="mysql://admin:123456789@127.0.0.1:3306/carinfo?serverVersion=5.7"
````
5. Jestliže daná struktura ještě v naší databázi neexistuje, vytvoříme ji pomocí. V případě, že daná struktura již existuje, je **nezbytné**, aby byla prázdná. V případě, že nebude prázdná, nemusí proběhnout migrace databáze korektně.
````shell
    $ php bin/console doctrine:database:create
````
6. Provedeme migraci databáze.
````shell
    $ php bin/console doctrine:migrations:migrate
````
7. Vzniklé tabulky naplníme hodnotami, které se nacházejí ve skriptu [`./sql/init.sql`](./sql/init.sql). V případě MySQL následovně. Tabulky není nutné plnit hodnotami, v takovém případě nám vznikne "čistá" aplikace. Pro účely snadnějšího seznámení se s aplikací naplnění doporučujeme.
````shell 
    $ sudo mysql carinfo < ./sql/init.sql
````
8. Za předpokladu že vše proběhlo korektně, je možné provést build projektu
````shell 
    $ composer install
    $ symfony server:ca:install
````
a následně jej spusit: 

````shell 
    $ symfony server:start
````
Symfony server je defaultně dostpný na [localhostu a portu 8000.](http://127.0.0.1:8000)

### Důležité údaje
V případě, že byla databáze naplněna hodnotami SQL skriptu, je k dispozici již vytořený uživatel, který eviduje několik vozidel. 
* login: ``user1@carinfo.cz`` 
* heslo: ``123456``

### Implementace
Odevzdaný archiv obsahuje ve svém kořenovém adresáři složku `src`. Obsah složky je podstatný pro samotný projekt. Ten je následující. Pozn.: Uvedený obsah je vypisován již ze složky src, ne z kořenového adresáře. Níže uvedená složka src je tedy zanořená v složce src v kořeném adresáři, plná cesta k ní tedy je `./src/src/`.
- `./migrations/` Obsahuje migraci nutnou pro zavedení databáze. 
- `./public/`
    * `./public/css/` Soubory kaskaádových stylů. 
    * `./public/js/` Javascript použitý v projektu.
- `./src/` Soubory backendu.
    * `./src/Controller/` Controllery nad jednotlivými akcemi, funkcemi apliakce. 
    * `./src/Entity/` Jednotlivé entity použité v projektu. 
    * `./src/Form/` Implementace všech formulářů. 
    * `./src/Repository/` Funkce vykonávané (pomocí SQL) nad databází. 
    * `./src/Security/` Autentifikátor pro přihlašování.
- `./templates/` Souboury frontendu, generování stránek, menu, ...
  
### Obsah archivu
- `doc` Dokumentace.
- `src` Zdrojové soubory, viz Implementace.
- `sql` Skripty pro zavedení databáze.