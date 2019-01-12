##-----------------------------------------------------------------------------
##                       hackhan - Make command
##-----------------------------------------------------------------------------
##   Author  : Nicolas DUPRE
##   Version : 0.5.0
##   Release : 14.03.2018
##
.PHONY: build clear env help install ls lsa ready reset shutdown start stop up service ssh hackhan
.DEFAULT_GOAL := help

DK=docker
DKC=docker-compose
DKM=docker-machine

CERR="196"
CINF="39"
CWARN="214"
CNOT="247"
CSUC="76"
CKWD="198"
CINP=$CKWD

DETACH="ctrl+q ctrl+d pour se detacher."
WAITSEC="Attendre quelque secondes..."
UNKNOW="Aucune commade specifiee ou inconnue."





##------------------------------[ Commandes ]----------------------------------
##   Liste des commandes :  make <cmd>
##
##   build     : (Re)construit les services
##   env       : Affiche les informations relatives à la Machine Virtuelle de
##               Docker.
##   help      : Affiche l'aide.
##   install   : Démarre l'environnement de developpement du projet.
##   ls        : Affiche la liste des containers actif.
##   lsa       : Affiche tous les containers existant.
##   reset     : Ré-initialise le project complet.
##   shutdown  : Arrête la machine virtuelle Docker sous Oracle VM VirtualBox.
##   ssh       : Se connecte à la VM Docker sous Oracle VM VirtualBox.
##   start     : Construit les services, créer et démarre les containers.
##   stop      : Arrête tous les containers.
##   up        : Créer et démarre les containers
##


build:
ifdef nocache
	@$(DKC) build --no-cache
else
	@$(DKC) build
endif


clear:
	@$(DK) rm -f $$($(DK) ps -a -q)


env:
	@$(DKM) env


help:
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'


install:
ifdef nocache
	@$(MAKE) --no-print-directory reset nocache=1
else
	@$(MAKE) --no-print-directory reset
endif
ifdef log
	@$(MAKE) --no-print-directory log
endif


log:
	@echo -e "\e[38;5;${CWARN}m!!! Attention, certain Shell ne peuvent interrompre la surveillance des logs avec ctrl+c !!!\e[0m"
	touch site/var/log/docker.log
	tail -f site/var/log/docker.log

ls:
	@$(DK) ps


lsa:
	@$(DK) ps -a


ready:
	@echo -e "\e[38;5;${CSUC}m--> Vous pouvez utiliser la commande make.\e[0m"
	@echo -e "\e[38;5;${CINF}m--> Exemple avec la commande 'make ls'.\e[0m"
	@$(DK) ps --all


reset:
	@$(MAKE) --no-print-directory stop
ifdef nocache
	@$(MAKE) --no-print-directory start nocache=1
else
	@$(MAKE) --no-print-directory start
endif
	@$(MAKE) --no-print-directory ls


shutdown:
	@$(DKM) stop default


start:
ifdef nocache
	@$(MAKE) --no-print-directory build nocache=1
else
	@$(MAKE) --no-print-directory build
endif
	@$(MAKE) --no-print-directory up


ssh:
	@echo -e "\e[38;5;${CSUC}mTapez 'exit' pour quitter la VM.\e[0m"
	$(DKM) ssh default


stop:
	@$(DKC) kill || echo -e "\e[38;5;${CERR}mTry 'source startDockerForWin7.sh'\e[0m"
	@$(DKC) rm -v --force


up:
	@$(DKC) up -d



##------------------------------[ Services ]-----------------------------------
##   Commande de manipulation des services :
##      make service <cmd>=<svc-name>
##      make svc     <cmd>=<svc-name>
##
##   Liste des command <cmd> :
##      create : Créé le service spécifié.
##      start  : Démarre le service spécifié avec un TTY.
##      stop   : Arrête le service spécifié.
##      attach : Ouvre un TTY avec le service spécifié.
##
##   Liste des service <svc-name> :
##      hackhan  : Service principal - Port: 8080
##

svc: service
service:
ifdef create
	@$(DK) run --name $(create) $(create) bash&
	@sleep 0.5
	@$(DK) ps
else ifdef start
	@echo $(DETACH)
	@$(DK) start $(start)
	@$(DK) exec -ti $(start) bash
else ifdef stop
	@echo $(WAITSEC)
	@$(DK) stop $(stop)
	@$(DK) ps
else ifdef attach
	@echo $(DETACH)
	@$(DK) exec -ti $(attach) bash
else
	@echo $(UNKNOW)
endif


hackhan:
	@$(MAKE) --no-print-directory svc attach=hackhan


##-----------------------------------------------------------------------------
