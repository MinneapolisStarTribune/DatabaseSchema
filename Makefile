#FLAGS=--verbose --debug
FLAGS=
#COVERAGE=--coverage-html coverage
COVERAGE=
DIR := ${CURDIR}
P=$(DIR)/vendor/bin/phpunit $(COVERAGE) $(FLAGS)

MYXML=-c phpunit.xml.dist

runtests: .ALWAYS
	$(P) $(MYXML)
	@echo $@ Complete

update: .ALWAYS
	composer update --dev && composer dump-autoload
	@echo $@ Complete

.ALWAYS:
