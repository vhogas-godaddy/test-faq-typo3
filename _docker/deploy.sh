set -euo pipefail

if [[ $# < 1 ]] ; then
    echo 'You need to add a argument live or latest'
    exit 1;
fi
IMAGE_NAME="hosteurope/storefront:$1"
if [ "$1" = "live" ]
then
    cd ../ && docker build -t $IMAGE_NAME --force-rm -f _docker/live/Dockerfile ./
else
    cd ../ && docker build -t $IMAGE_NAME --force-rm -f _docker/staging/Dockerfile ./
fi
IMAGEID=$(docker images  -aq $IMAGE_NAME)
docker tag $IMAGEID docker-emea.artifactory.ba.heg.com/$IMAGE_NAME
docker push docker-emea.artifactory.ba.heg.com/$IMAGE_NAME
docker rmi -f $IMAGEID