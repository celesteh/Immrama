# 'Makefile'
src = $(wildcard *.md)
html = $(src:.md=.html)
MDFLAGS = --css ../style.css

.PHONY: all
all: $(html)
		#pandoc -o $@ $^ $(MDFLAGS)

%.html: %.md
		pandoc $< -o $@ $(MDFLAGS)

.PHONY: install
install: $(all)
	  cp *html ../data/help/

.PHONY: clean
clean:
	    rm -f *.bak *~ *.html
