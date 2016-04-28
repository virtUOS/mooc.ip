# makefile for mooc/locale

# list of message catalogs to create
CATALOGS = locale/en/LC_MESSAGES/mooc.mo

# list of translatable source files
SOURCES = $(wildcard *.php */*.php */*/*.php */*/*/*.php)

# default rules for gettext handling
%.pot: $(SOURCES)
	xgettext -k_mooc -o $@ --from-code=ISO-8859-1 $(SOURCES)
	msgconv -t ISO-8859-1 -o $@ $@

%.po: %.pot
	msgmerge -qU $@ $<

%.mo: %.po
	msgfmt -o $@ $<

# default target: create all message catalogs
all: $(CATALOGS)
