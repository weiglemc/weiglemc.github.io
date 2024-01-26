# BibTeX to Markdown generator

## Bibtex files

* `mweigle.bib` - my publications
* `students-thesis.bib` - my students' MS theses and dissertations

## Python

* `bib2md.py` - Python script to generate my publication lists, default BibTeX input file `./mweigle.bib`, default output dir `../_publications/`
  * Usage: `% python3 bib2md.py [bibtex_file] [output_dir]`
* `bib2md-students.py` - Python script to generate Markdown entries for student theses and dissertations, generates `./students-thesis.md` to then be copy/pasted into  `_pages/students.md`
  * Usage: `% python3 bib3md-students.py`
* `bibtexref3_md.py` - Python version of PHP library based on [BibtexRef plugin from PmWiki](https://www.pmwiki.org/wiki/Cookbook/BibtexRef) (used by both scripts above)

Python version was updated January 2024.  Initial conversion from PHP was made by ChatGPT with bug fixes and updates by M. Weigle.

## PHP

* `bib2md-students.php` - PHP to generate `students-thesis.md`
* `bib2md.php` - PHP to generate my entries in `_publications/`
* `bibtexref3-md.php` - library based on [BibtexRef plugin from PmWiki](https://www.pmwiki.org/wiki/Cookbook/BibtexRef)

PHP version originally added in July 2021.

## Jupyter notebook Markdown generator

*MCW: I don't use any of these files, have been moved to `unused/` folder.*

These .ipynb files are Jupyter notebook files that convert a TSV containing structured data about talks (`talks.tsv`) or presentations (`presentations.tsv`) into individual markdown files that will be properly formatted for the academicpages template. The notebooks contain a lot of documentation about the process. The .py files are pure python that do the same things if they are executed in a terminal, they just don't have pretty documentation.

* `publications.ipynb`
* `publications.py`
* `publications.tsv`
* `PubsFromBib.ipynb`
* `pubsFromBib.py`
* `talks.ipynb`
* `talks.py`
* `talks.tsv`
