targetPath="/var/www/html/Projects/distribution/TrainEasy/Application"
#copy entire application
rm -rf $targetPath
mkdir $targetPath
cp -rf * $targetPath
echo "Done copying"
