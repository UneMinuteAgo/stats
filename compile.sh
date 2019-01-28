#!/usr/bin/env bash
# Compilation du README en PDF + Git pour ne pas oublier de le commit
mpdf README.md --preset=uma && git add README.*
