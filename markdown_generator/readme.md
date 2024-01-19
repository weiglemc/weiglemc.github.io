# BibTeX to Markdown generator


## Bibtex files:

* `mweigle.bib` - my publications
* `students-thesis.bib` - my students' MS theses and dissertations, used to generate `students-thesis.md`, copy entry to `_pages/students.md`

## Python

* `bibtexref3_md.py` - Python version of PHP library based on [BibtexRef plugin from PmWiki](https://www.pmwiki.org/wiki/Cookbook/BibtexRef)
* `bib2md.py` - Python script to generate my publication listsx, files written to `_publications/`
	* Usage: `% python3 bib2md.py`
* `bib2md-students.py` - Python script to generate Markdown entries for student theses and dissertations
	* Usage: `% python3 bib3md-students.py`


Python version was updated January 2024.  Initial conversion from PHP was made by ChatGPT with bug fixes and updates by M. Weigle. 

## PHP 

* `bib2md-students.php` - PHP to generate `students-thesis.md`
* `bib2md.php` - PHP to generate my entries in `_publications/`
* `bibtexref3-md.php` - library based on [BibtexRef plugin from PmWiki](https://www.pmwiki.org/wiki/Cookbook/BibtexRef)

PHP version originally added in July 2021.

## Jupyter notebook Markdown generator

*I don't use any of these files, have been moved to `unused/` folder.*

These .ipynb files are Jupyter notebook files that convert a TSV containing structured data about talks (`talks.tsv`) or presentations (`presentations.tsv`) into individual markdown files that will be properly formatted for the academicpages template. The notebooks contain a lot of documentation about the process. The .py files are pure python that do the same things if they are executed in a terminal, they just don't have pretty documentation.

* `publications.ipynb`
* `publications.py`
* `publications.tsv`
* `PubsFromBib.ipynb`
* `pubsFromBib.py`
* `talks.ipynb`
* `talks.py`
* `talks.tsv`
