# phone-book

- vagrant up
- vagrant ssh
- composer install
- exit
- sample create phone book
curl -k -X POST https://172.21.99.4/phone-book -d '{"first_name":"John", "last_name": "Doe", "phone_number":"+12 223", "country_code":"AD", "time_zone":"America/Yakutat"}' -H "Content-Type: application/json"
- expected result
{"status":"success","result":[{"id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7","first_name":"John","last_name":"Doe","phone_number":"+12 223","country_code":"AD","time_zone":"America\/Yakutat"}]}

- sample get a single phone book
curl -k -X GET https://172.21.99.4/phone-book/5befe986-9fbf-4d6b-bce3-2aa9901d13a7
access to browser with https://172.21.99.4/phone-book/5befe986-9fbf-4d6b-bce3-2aa9901d13a7
- expected result
{"status":"success","result":[{"id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7","first_name":"John","last_name":"Doe","phone_number":"+12 223","country_code":"AD","time_zone":"America\/Yakutat"}]}

- sample find phone book
find all
curl -k -X GET https://172.21.99.4/phone-book
access to browser with https://172.21.99.4/phone-book
expected result
{"status":"success","result":[{"id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7","first_name":"John","last_name":"Doe","phone_number":"+12 223","country_code":"AD","time_zone":"America\/Yakutat"}]}

find by name
curl -k -X GET https://172.21.99.4/phone-book\?first_name\=john
or access via browser https://172.21.99.4/phone-book?first_name=john
expected result
{"status":"success","result":[{"id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7","first_name":"John","last_name":"Doe","phone_number":"+12 223","country_code":"AD","time_zone":"America\/Yakutat"}]}

pagination
curl -k -X GET https://172.21.99.4/phone-book\?offset\=1\&numItems\=1
or access via browser https://172.21.99.4/phone-book?offset=1&numItems=1
expected result
{"status":"success","pagination":{"total":1,"current":1,"previous":1,"next":1,"last":1},"result":[{"id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7","first_name":"John","last_name":"Doe","phone_number":"+12 223","country_code":"AD","time_zone":"America\/Yakutat"}]}

update
curl -k -X PATCH https://172.21.99.4/phone-book/5befe986-9fbf-4d6b-bce3-2aa9901d13a7 -d '{"first_name":"Michael","last_name":"Corleone","phone_number":"+12 223","country_code":"AD","time_zone":"America\/Yakutat"}' -H "Content-Type: application/json"
expected result
{"status":"success","result":[{"id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7","first_name":"Michael","last_name":"Corleone","phone_number":"+12 223","country_code":"AD","time_zone":"America\/Yakutat"}]}

delete
curl -k -X DELETE https://172.21.99.4/phone-book/5befe986-9fbf-4d6b-bce3-2aa9901d13a7
expected result
{"status":"success","result":[{"id":"5befe986-9fbf-4d6b-bce3-2aa9901d13a7","first_name":"Michael","last_name":"Corleone","phone_number":"+12 223","country_code":"AD","time_zone":"America\/Yakutat"}]}

check the phone book was deleted
curl -k -X GET https://172.21.99.4/phone-book/5befe986-9fbf-4d6b-bce3-2aa9901d13a7
expected result
{"status":"fail","message":"Phone book not found"}

execute unit test
vagrant ssh
cd tests
../vendor/bin/phpunit

access to mysql
vagrant ssh
mysql -u hostaway -phostaway
use hostaway
select * from phone_book
