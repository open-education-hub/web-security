RVMD = reveal-md

.PHONY: all html clean

all: html

html: _site

_site: slides.md ../../common/favicon.ico
	$(RVMD) $< --static $@
	cp ../../common/favicon.ico $@

clean:
	-rm -f *~
	-rm -fr _site
