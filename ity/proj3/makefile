IMAGES_DIRECTORY=./obrazky
PACKAGES=algorithm2e.sty

proj3.pdf: proj3.ps
	ps2pdf -sPAPERSIZE=a4 $^

proj3.ps: proj3.dvi
	dvips $^

proj3.dvi: proj3.tex
	latex $^
	latex $^

pdflatex: proj3.tex
	pdflatex $^
