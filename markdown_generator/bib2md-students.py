# bib2md.py
# Generate Jekyll-compatable .md file from BibTeX file
#   - single file with student thesis and dissertations
#   - I copy the result into my students.md page that lists all current/former students
# Based on BibtexRef plugin for PmWiki, https://www.pmwiki.org/wiki/Cookbook/BibtexRef
#   - modified to generate Markdown - Michele Weigle, July 2021 
# Example commands (from bibtexref3-md.php):
#    $reftag = "jones-websci21";
#    CompleteBibEntry($bibTexFile, $reftag);
#    BibSummary($bibTexFile, $reftag);
#    BibCite($bibTexFile, $reftag);
# updated from PHP to Python, MCW, Jan 18, 2024

from bibtexref3_md import *

error_reporting = "E_ALL"
display_errors = "1"

bibTexFile = 'students-thesis.bib'
outputDir = '.'

# generate one line per student
tags = ['jayanetti-ms23', 'mccoy-phd22', 'aturban-phd20', 'kelly-phd19', 'alkwai-phd19', 'almaksousy-phd18',
    'mohrehkesh-phd15', 'almalag-phd13', 'arbabi-phd11', 'ibrahim-phd11', 'yan-phd10',
    'jayanetti-ms23', 'berlin-ms18', 'kelly-ms12', 'padia-ms12', 'adurthi-ms06', 'sharma-ms06']

file_path = os.path.join(outputDir, "students-thesis.md")
with open(file_path, "w") as fp:
    for tag in tags:
        paper_string = bib_summary(bibTexFile, tag, False)
        fp.write(paper_string)
        fp.write("\n\n")
