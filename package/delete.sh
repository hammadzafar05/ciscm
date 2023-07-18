targetPath="/var/www/html/Projects/distribution/TrainEasy/Application"
cd $targetPath
rm -rf pakcage/
rm -rf storage/
mkdir "storage"
mkdir "storage/logs"
mkdir "storage/app"
mkdir "storage/framework"
mkdir "storage/framework/sessions"
mkdir "storage/framework/cache"
mkdir "storage/framework/views"
mkdir "storage/tmp"
find ./storage -type d -exec chmod 777 {} \;
find ./bootstrap -type d -exec chmod 777 {} \;
find ./resources/lang -type d -exec chmod 777 {} \;
#chmod 777 storage
#chmod 777 storage/logs
rm -rf uploads/
rm saas.sh
rm package.sh
rm readme.md
rm -rf public/tmp/cache/*
chmod 777 public/tmp/cache
mkdir "public/tmp/cache/img"
chmod 777 public/tmp/cache/img
mkdir "public/tmp/cache/uploads"
chmod 777 public/tmp/cache/uploads
mkdir "public/tmp/cache/usermedia"
chmod 777 public/tmp/cache/usermedia
rm -rf public/uploads/*
chmod 777 public/uploads
rm -rf public/usermedia/*
chmod 777 public/usermedia
rm -rf public/uservideo/*
chmod 777 public/uservideo

cp /var/www/html/Projects/TrainEasyV3/package/usermedia/.htaccess "$targetPath/public/usermedia"
chmod 777 public/usermedia/.htaccess

cp /var/www/html/Projects/TrainEasyV3/package/uservideo/.htaccess "$targetPath/public/uservideo"
chmod 777 public/uservideo/.htaccess

rm -rf node_modules/
rm -rf samples/
#find resources/lang/ -type f -exec sed -i 's|CareMate|TrainEasy|g' {} \;
conDir="public/templates/buson/assets/Consulting Doc"
rm -rf $conDir
find public/themes/ -type f -name "*.html" -delete
find public/templates/ -type f -name "*.html" -delete
find public/templates/ -type f -name "*.txt" -delete
find ./ -type f -name "README.md" -delete
find ./ -type f -name "readme.md" -delete
find vendor/ -type f -name "*.txt" -delete
find public/ -type f -name "*.html" -delete
#rm .env
cp /var/www/html/Projects/TrainEasyV3/storage/.env $targetPath
chmod 777 .env
