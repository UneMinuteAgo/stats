#!/usr/bin/env bash

#---------------------------------------------------------
#          OriginS-Esport - stopDockerForWin7.sh
#---------------------------------------------------------
# Author  : Nicolas DUPRE
# Version : 0.1.0
# Release : 15.02.2018
#
# Uniqument pour les utilisateurs Windows 7.
# Utilisez le script suivant pour stopper proprement la machine virtuelle Docker.
#

# Stopper la machine virtuelle "Default" créée par Docker.
docker-machine stop default
