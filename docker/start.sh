#!/bin/sh

build/HelperScripts/update_tabdata

build/HelperScripts/createuserfiles

build/coreBOSTests/phpunit -c build/coreBOSTests/phpunit.xml