# 'Makefile'
src = $(wildcard *.md)
html = $(src:.md=.html)
pdf = $(src:.md=.pdf)
HTMLFLAGS = --css ../style.css --css ./help.css --standalone

.PHONY: all
all: $(html) $(pdf)
		#pandoc -o $@ $^ $(HTMLFLAGS)

%.html: %.md
		pandoc $< -o $@ $(HTMLFLAGS)

%.pdf: %.md
		pandoc $< -o $@

.PHONY: install
install: $(all)
	  cp *html ../data/help/
		cp *pdf ../data/help/
		cp *png ../data/help/

.PHONY: clean
clean:
	    rm -f *.bak *~ *.html *.pdf
