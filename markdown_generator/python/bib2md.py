## WORK IN PROGRESS

from bibtexref3_md import *

def generate_file(output_dir, outfile, title, type_value, permalink, paper_string):
    file_path = os.path.join(output_dir, outfile)
    with open(file_path, "w") as fp:
        fp.write("---\n")
        fp.write(f"title: \"{title}\"\n")
        fp.write(f"type: '{type_value}'\n")
        fp.write(f"permalink: {permalink}\n")
        fp.write("collection: 'publications'\n")
        fp.write("doi-color: '#fcab22'\n")
        fp.write("acrobat-color: '#f70e0c'\n")
        fp.write("blogger-color: '#F37100'\n")
        fp.write("---\n")
        fp.write(paper_string)

def generate_files(bibTexFile, outputDir):
    years = list(range(1997, 2024))
    years.remove(1998)  # Skipping the year 1998 and 2002
    years.remove(2002)

    for year in years:
        if year == 1998 or year == 2002:
            continue
        outfile = f"{year}.md"
        paper_string = BibQuery(bibTexFile, f"(\$this.get('YEAR') == {year})", "!\$this.get('PUBDATE')", "100")
        generate_file(outputDir, outfile, str(year), "year", f"/publications/{year}", paper_string)

    types = ["book", "journal", "conference", "techreport", "other"]
    for index, type_value in enumerate(types, start=1):
        outfile = f"{index}-{type_value}.md"
        if type_value == "book":
            title = "Books and Book Chapters"
            paper_string = BibQuery(bibTexFile, "strpos(\$this.get('ENTRYTYPE'),'BOOK')!==FALSE || strpos(\$this.get('ENTRYTYPE'),'INCOLLECTION')!==FALSE", "!\$this.get('PUBDATE')", "100")
        elif type_value == "journal":
            title = "Journals and Magazines"
            paper_string = BibQuery(bibTexFile, "strpos(\$this.get('ENTRYTYPE'),'ARTICLE')!==FALSE", "!\$this.get('PUBDATE')", "100")
        elif type_value == "conference":
            title = "Conferences and Workshops (Peer-Reviewed)"
            paper_string = BibQuery(bibTexFile, "strpos(\$this.get('ENTRYTYPE'),'INPROCEEDINGS')!==FALSE", "!\$this.get('PUBDATE')", "100")
        elif type_value == "techreport":
            title = "Tech Reports"
            paper_string = BibQuery(bibTexFile, "strpos(\$this.get('ENTRYTYPE'),'TECHREPORT')!==FALSE", "!\$this.get('PUBDATE')", "100")
        elif type_value == "other":
            title = "Other (Poster Presentations, Dissertation, Misc)"
            paper_string = BibQuery(bibTexFile, "strpos(\$this.get('ENTRYTYPE'),'MISC')!==FALSE || strpos(\$this.get('ENTRYTYPE'),'PHDTHESIS')!==FALSE", "!\$this.get('PUBDATE')", "100")

        generate_file(outputDir, outfile, title, "type", f"/publications/{type_value}", paper_string)

    # generate single file with full BibTeX entry for all
    outfile = "bibtex.md"
    paper_string = ""
    bibentries = ParseBibFile(bibTexFile)
    for entry in bibentries:
        for bib in entry:
            ref = bib.entryname
            paper_string += CompleteBibEntry(bibTexFile, ref)
    generate_file(outputDir, outfile, "BibTeX", "bibtex", "/publications/bibtex", paper_string)

    # generate single file with recent publications (since $year)
    outfile = "recent.md"
    year = 2022
    paper_string = BibQuery(bibTexFile, f"(\$this.get('YEAR') >= \"{year}\")", "!\$this.get('PUBDATE')", "10")
    generate_file(outputDir, outfile, "Recent Publications", "recent", "/publications/recent", paper_string)

    # generate single file with award publications
    outfile = "award.md"
    paper_string = BibQuery(bibTexFile, "(\$this.get('KEYWORD') == 'award')", "!\$this.get('PUBDATE')", "100")
    generate_file(outputDir, outfile, "Award Publications", "award", "/publications/award", paper_string)

if __name__ == "__main__":
    error_reporting = "E_ALL"
    display_errors = "1"

    bibTexFile = 'mweigle.bib'
    # outputDir = '../_publications'
    outputDir = '.'  # has to be '.' for generating with ODU CS webserver

    generate_files(bibTexFile, outputDir)
