targetPath="/var/www/html/Projects/distribution/TrainEasy/Application/"
rm /var/www/html/Projects/distribution/TrainEasy/app.zip
rm /var/www/html/Projects/distribution/TrainEasy/TrainEasy.zip
cd $targetPath
zip -r app.zip .
mv app.zip ../
cd ../
zip -r TrainEasy.zip app.zip Documentation Resources
rm app.zip
nautilus .
