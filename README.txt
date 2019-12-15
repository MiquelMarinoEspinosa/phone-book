* Setup environment
- Requirements
	Virtualbox
	Vagrant
	Ansible

- Versions used during de test development
	Virtualbox 6.0
	Vagrant 2.2.6
	Ansible 2.9.2	

- Commands to run up the environment
	vagrant up
	vagrant ssh
	composer install
	exit

- Stack used as infrastructure contained inside the Vagrant machine
	Linux debian/jessie64 box
	Nginx 1.6.2
	Mysql 14.14 Distrib 5.5.62
	php-fpm 7.3
	phalcon framework 

- Commands to access to the mysql server
	vagrant ssh
	mysql -u hostaway -phostaway
	USE hostaway
	SELECT * FROM phone_book

- Execute php unit test
	vagrant ssh
	cd tests
	../vendor/bin/phpunit

* API endpoints requests
- Samples using the command line from the localhost

	Create phone book
curl -k -X POST https://172.21.99.4/phone-book -d '{"first_name":"John", "last_name": "Doe", "phone_number":"+12 223", "country_code":"AD", "time_zone":"America/Yakutat"}' -H "Content-Type: application/json"

	Expected result
{ 
   "status":"success",
   "result":[ 
      { 
         "id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7",
         "first_name":"John",
         "last_name":"Doe",
         "phone_number":"+12 223",
         "country_code":"AD",
         "time_zone":"America\/Yakutat"
      }
   ]
}

	Get a single phone book by ID
curl -k -X GET https://172.21.99.4/phone-book/5befe986-9fbf-4d6b-bce3-2aa9901d13a7
access to browser with https://172.21.99.4/phone-book/5befe986-9fbf-4d6b-bce3-2aa9901d13a7

	Expected result
{ 
   "status":"success",
   "result":[ 
      { 
         "id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7",
         "first_name":"John",
         "last_name":"Doe",
         "phone_number":"+12 223",
         "country_code":"AD",
         "time_zone":"America\/Yakutat"
      }
   ]
}

	Find all phone books
curl -k -X GET https://172.21.99.4/phone-book
Access via browser https://172.21.99.4/phone-book

	Expected result
{ 
   "status":"success",
   "result":[ 
      { 
         "id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7",
         "first_name":"John",
         "last_name":"Doe",
         "phone_number":"+12 223",
         "country_code":"AD",
         "time_zone":"America\/Yakutat"
      }
   ]
}

	Find by first name
curl -k -X GET https://172.21.99.4/phone-book\?first_name\=john
Access via browser https://172.21.99.4/phone-book?first_name=john

	Expected result
{ 
   "status":"success",
   "result":[ 
      { 
         "id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7",
         "first_name":"John",
         "last_name":"Doe",
         "phone_number":"+12 223",
         "country_code":"AD",
         "time_zone":"America\/Yakutat"
      }
   ]
}

	Find using pagination
curl -k -X GET https://172.21.99.4/phone-book\?offset\=1\&numItems\=1
Access via browser https://172.21.99.4/phone-book?offset=1&numItems=1

	Expected result
{ 
   "status":"success",
   "pagination":{ 
      "total":1,
      "current":1,
      "previous":1,
      "next":1,
      "last":1
   },
   "result":[ 
      { 
         "id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7",
         "first_name":"John",
         "last_name":"Doe",
         "phone_number":"+12 223",
         "country_code":"AD",
         "time_zone":"America\/Yakutat"
      }
   ]
}

	Update a phone book
curl -k -X PATCH https://172.21.99.4/phone-book/5befe986-9fbf-4d6b-bce3-2aa9901d13a7 -d '{"first_name":"Michael","last_name":"Corleone","phone_number":"+12 223","country_code":"AD","time_zone":"America\/Yakutat"}' -H "Content-Type: application/json"

	Expected result
{ 
   "status":"success",
   "result":[ 
      { 
         "id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7",
         "first_name":"Michael",
         "last_name":"Corleone",
         "phone_number":"+12 223",
         "country_code":"AD",
         "time_zone":"America\/Yakutat"
      }
   ]
}

	Delete a phone book
curl -k -X DELETE https://172.21.99.4/phone-book/5befe986-9fbf-4d6b-bce3-2aa9901d13a7

	Expected result
{ 
   "status":"success",
   "result":[ 
      { 
         "id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7",
         "first_name":"Michael",
         "last_name":"Corleone",
         "phone_number":"+12 223",
         "country_code":"AD",
         "time_zone":"America\/Yakutat"
      }
   ]
}

	Check that the phone book was deleted
curl -k -X GET https://172.21.99.4/phone-book/5befe986-9fbf-4d6b-bce3-2aa9901d13a7

	Expected result
{ 
   "status":"fail",
   "message":"Phone book not found"
}

* Folders structure
	
	|- apps
	|
	|- etc 
	|
	|- public
	|
	|- tests

	- apps
Contains the application files. This files implements the features needed to process the endpoints request and the data base management data.

	- etc
Contains the files which implements the playbooks needed to provision the Vagrant machine with all the application needed by the application.
	
	- public
Contains the index.php to bootstrap the application, manage the incoming application requests and setup all the configurations needed

	- tests
Contains the application unit tests

* Public
	|- public
	    |- index.php

	Routing
	- The index.php is the file that was used to do the bootstrap application. Using the phalcon  framework the index.php is the first file which receives the incoming requests forwarded by the nginx web server. The file is mainly in charge to process the endpoint requests to route them to the their handler as well as setup the basic configuration for the good sake of the application features.
Notice that the http verbs were used to distinct the different request. This way is not necessary to include the operation as part of the url. Instead the right http verb should be used to indicate which action would like to fulfill.
	
	GET - find phone books
	POST - create phone books
	PATCH - update phone books
	DELETE - delete phone books

Once the route has been match to a handler the application delegates on the handler to process the request.

	Container
	- The file has the responsibility of including all the necessary files and define the namespaces to make the files accesible.
The configuration of the data base, cache and additional services are done while the creation of the container which will contain the different services make them accesibles for other files in the application.
	
	Logger
	- Initialize the logger using the BooBoo library to format the application errors as json structure - to stick to the format API endpoints response.
Sample of an error formatted
{ 
   "severity":"Exception",
   "type":"Exception",
   "code":0,
   "message":"An exception has occured",
   "file":"\/var\/deployments\/phone-book\/releases\/1\/public\/index.php",
   "line":138,
   "trace":[ 

   ]
}    	

* Apps
	|- apps
	    |- controllers
	    |	  |- PhoneBookController.php
	    |	
	    |- models
	    |	  |- PhoneBook.php
	    |	
	    |- services
	          |- CountryService.php
		  |- TimeZoneService.php

	Controllers
	- The controller PhoneBookController is the handler which process the request matched previously by the index.php. The different requests are redirected and matched to methods of the class
		* createAction --> create phone book POST
		* getAction --> find phone book by id GET
		* findAction --> find all phone book, find by name, find pagination GET
		* updateAction --> update a phone book by id PATCH
		* deleteAction --> delete a phone book by id DELETE  
	
	- The controller basically does
		* Receive request parameters as the function parameters
		* Process the parameters
		* Perform the actions needed using the Models which are linked to the ORM
		* Format and send the response to the index.php which at the same time forward the response to the nginx web server
		* Handle and format the exception which might occurred during the process

	- The controller use the models offered by the ORM to access and manage the data base data.

	Models
	- Using Phalcon\Mvc\Model as ORM the class PhoneBook connects to the mysql which is linked to the phone_book table

CREATE TABLE IF NOT EXISTS phone_book (
    id varchar(255) NOT NULL,
    firstName varchar(255) NOT NULL,
    lastName varchar(255),
    phoneNumber varchar(100) NOT NULL,
    countryCode char(2),
    timeZone varchar(100),
    insertedOn DATETIME NOT NULL,
    updatedOn DATETIME NOT NULL,
    PRIMARY KEY (id)
);

	- The PhoneBook class perform the different validations exposing setters public methods which contains the logic to validate each field

	- There is an extra method called hydrate which is used by the PhoneBookController class to pass a set of parameters to the PhoneBook class and delegate the responsibility to filled the its fields with the data

	- The class access to 2 extra services - country_service and time_zones_service - through the container for validation purposes

	Services
	- Two extra services were implemented to hide the complexity of retrieving the countries and time zones data applying the SOLID SRP principle
	- A memcached cache is use to highly improve the performance caching the data for 1 hour. If the data is not cached for some request the class retrieve the information from the endpoint and cache the data into the memcached. For the next hour the rest of the requests will be served using the cache. Without doing extensive performance profiling it has been noticed that the request highly speed up using the cache
	- The guzzle library is used to retrieve the data for the endpoint provided in the test documentation
	- The dependencies are injected during the container's creation in the index.php file applying the SOLID DIP principle

* etc
	|- etc
	   |- devel
		|- vagrant
		      |- provision
			     |- ansible
				   |- apps
				   |- playbooks
				   |- templates

	playbooks
	- Contains the entry point file to start the provision - playbook.yml
	- This file delegates in oder two files
		tasks/update_system.yml: in charge to update the Debian repositories and install the general packages
		../apps/apps.yml: in charge to install each application with all the needed configurations and packages to run the infrastructure properly
	
	apps

	|- ansible
	      |- apps
		  |- mysql
		  |- nginx
		  |- php
		  |- apps.yml

	- The structure of folders is split for each application installed
	- Each applications installation will use eventually files included in the templates folder which define the customer configuration for each application
	- The apps.yml includes all the main yml files of each application and delegates on them to configure each application individually

	templates
	
	|- ansible
	      |- templates
		  |- mysql
		  |- nginx
		  |- php

	- Contains the .ini, .conf, .sql and so on configurations files for each application
	- During the execution of the apps playbooks installation will retrieve this files to customize the configuration

* tests

	|- tests
	     |- Test
	     |	  |- PhoneBookTest
	     |- phpunit.xml
	     |- TestHelper.php
	     |- UnitTestCase.php

	- Contains all the need it configuration to implements unit tests for the application
	- The PhoneBookTest is an unit test to test some methods of the class because contains the most of the logical business - validations
	- One interesting test is testNotExistentCountryCodeShouldThrownAnException where the country service is mocked using the prophecy library which is injected to the container. Eventually during the test the mock class will be used instead of the original class avoiding accessing to external resources made the test more reliable

* Future work
	- Add outh2.0 as an API authentication
	- Add more unit tests to increase the coverage
	- Add integration - acceptance tests using frameworks such as beta
	- Improve the ansible provision using roles and vars to avoid config duplication
	- Use .yml files to define the container dependencies and refactor the index.php
	- Configure the /etc/hosts to bind the vagrant ip address to a domain automatically 