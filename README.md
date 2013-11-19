**MANAŽMENT SKOLSKÝCH PROJEKTOV**

##Instalation
1. Install & configure git

http://git-scm.com/book/en/Getting-Started-Installing-Git
http://guides.beanstalkapp.com/version-control/git-on-mac.html

2. Clone repository

To clone a repository means that you will download the whole code of the repository. 
Obviously, your are on a Mac, so launch your Terminal, go to your php server folder and type
- git@github.com:fleetlog/pom.git
 
you will download the code.

3. Install composer 

  - homepage: http://getcomposer.org/
  - instalation: http://second-cup-of-coffee.com/installing-composer-on-mac-os-x-mountain-lion/
  - run in project directory: install composer

4. Database (user: root, password: root)
  - send Erik your ssh public key :D
  - create mysql database wtmsp (webove technologie manazer skolskych projektov)
  - download table & data:
    - run download script: sh ./scripts/download.db.sh

5. Run page

##Project description Brano

 - www/ : css, images, js
 - load BASIC css, js : app/MainModule/templates/@layout.latte
 
 - TEMPLATES STRUCTURE:
  - app/MainModule/templates/@layout.latte : dizajn spoločný pre ucitela a studenta (logo, ...)
  - app/MainModule/MentorModule/templates : ucitelove sablony
    - @layout.latte : zakladna ucitelova sablona (ine menu polozky ako student alebo tak)
  - app/MainModule/StudentModule/templates : studentove sablony
    - @layout.latte : zakl. sablona pre studenta

##DOWNLOAD/UPLOAD DATABASE

- run download script: sh ./scripts/download.db.sh
- run upload script: sh ./scripts/upload.db.sh
