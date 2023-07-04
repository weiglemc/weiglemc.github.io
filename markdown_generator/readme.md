# PHP bibtexref3-based markdown generator

* `bib2md-students.php` - PHP to generate `students-thesis.md`
* `bib2md.php` - PHP to generate my entries in `_publications/`
* `bibtexref3-md.php` - based on BibtexRef plugin for PmWiki, <https://www.pmwiki.org/wiki/Cookbook/BibtexRef>

Bibtex files:

* `mweigle.bib` - my publications
* `students-thesis.bib` - my students' MS theses and dissertations, used to generate `students-thesis.md`, copy entry to `_pages/students.md`

# Jupyter notebook markdown generator

*MCW: I don't use any of these files*

These .ipynb files are Jupyter notebook files that convert a TSV containing structured data about talks (`talks.tsv`) or presentations (`presentations.tsv`) into individual markdown files that will be properly formatted for the academicpages template. The notebooks contain a lot of documentation about the process. The .py files are pure python that do the same things if they are executed in a terminal, they just don't have pretty documentation.

* `publications.ipynb`
* `publications.py`
* `publications.tsv`
* `PubsFromBib.ipynb`
* `pubsFromBib.py`
* `talks.ipynb`
* `talks.py`
* `talks.tsv`
