# bibtexref3-md.py
import os
import re

from operator import itemgetter, attrgetter

BibtexPdfLink = "PDF"
BibtexDoiLink = "DOI"
BibtexUrlLink = "URL"
BibtexBibLink = "BibTeX"
BibtexPreprintLink = "preprint"  # added link to preprint -MCW
BibtexSlidesLink = "slides"  # added link to slides -MCW
BibtexPosterLink = "poster"  # added link to poster -MCW
BibtexArxivLink = "arXiv"  # added link to arXiv.org version -MCW 12/18/13
BibtexTripReportLink = "trip report"  # added link to trip report -MCW 5/28/14

# Markdown buttons/icons
BibtexLinkIcon = "<i class='fas fa-fw fa-link'></i>"
BibtexDoiIcon = "<i class='ai ai-fw ai-doi' style='color: {{ page.doi-color }}'></i>"
BibtexPdfIcon = "<i class='fas fa-solid fa-file-pdf' style='color: {{ page.acrobat-color }}'></i>"
BibtexTripIcon = "<i class='fab fa-blogger' style='color: {{ page.blogger-color }}'></i>"
BibtexSlideshareButton = "class='btn btn--mcwslideshare'><img src='../images/slideshare-16px-high.png'/>"
BibtexArxivButton = "class='btn btn--mcwarxiv'><img src='../images/arxiv-logo-16px-high.png'/>"
BibtexBibtexButton = "class='btn btn--mcwbibtex'><img src='../images/BibTeX_logo-16px-high.png'/>"

BibtexGenerateDefaultUrlField = False
BibtexCompleteEntriesUrl = ""

TitleLinkDOIURL = False

BibtexLang = {}

BibEntries = {}
OrigBibEntries = {}

def sort_by_field(a, b):
    global SortField
    f1 = a.eval_get(SortField)
    f2 = b.eval_get(SortField)
    if f1 == f2:
        return 0
    return -1 if f1 < f2 else 1


def bib_query(files, cond, sort, max_entries):
    global BibEntries, SortField

    ret = ""

    files = files.strip()
    cond = cond.strip()
    sort = sort.strip()

    reverse = False
    if sort.startswith("!"):
        reverse = True
        sort = sort[1:]

    if cond == "":
        cond = "True"

    if files not in BibEntries:
        if not parse_bib_file(files):
            return "%red%Invalid BibTex File!"

    res = []
    bib_selected_entries = BibEntries[files]
    for entry in bib_selected_entries:
        if entry.eval_cond(cond):
            res.append(entry)

    if sort:
        SortField = sort
        res.sort(key=lambda x: x.eval_get(SortField))

    if reverse:
        res.reverse()

    if max_entries:
        res = res[:int(max_entries)]

#    for index, entry in enumerate(res, 1):
#        ret += f"{index}. {entry.get_summary()}\n"
    for index, entry in enumerate(res, 1):
        ret += f"1. {entry.get_summary()}\n"

    return ret


def get_entry(bib, ref):
    global BibEntries

    ref = ref.strip()
    bib = bib.strip()

    if bib not in BibEntries:
        parse_bib_file(bib)

    bib_table = BibEntries[bib]

    for entry in bib_table:
        if entry.get_name() == ref:
            return entry

    return None


def bib_cite(bib, ref):
    entry = get_entry(bib, ref)
    if not entry:
        return "%red%Invalid BibTex Entry!"
    return entry.cite()


def complete_bib_entry(bib, ref):
    entry = get_entry(bib, ref)
    if not entry:
        return "%red%Invalid BibTex Entry!"
    return entry.get_complete_entry()


def bib_summary(bib, ref, do_bibtex=True):
    entry = get_entry(bib, ref)
    if not entry:
        return "%red%Invalid BibTex Entry!"
    return entry.get_summary(do_bibtex)


def parse_entries(fname, entries):
    global BibEntries, OrigBibEntries
    BibEntries[fname] = []

    for i in range(len(entries)):
        entrytype = entries[i][0].upper()
        entryname = entries[i][1]

        if entrytype == "ARTICLE":
            entry = Article(fname, entryname)
        elif entrytype == "INPROCEEDINGS":
            entry = InProceedings(fname, entryname)
        elif entrytype == "PHDTHESIS":
            entry = PhdThesis(fname, entryname)
        elif entrytype == "MASTERSTHESIS":
           entry = MasterThesis(fname, entryname)
        elif entrytype == "INCOLLECTION":
            entry = InCollection(fname, entryname)
        elif entrytype == "BOOK":
            entry = Book(fname, entryname)
        elif entrytype == "INBOOK":
            entry = InBook(fname, entryname)
        elif entrytype == "TECHREPORT":
            entry = TechReport(fname, entryname)
        elif entrytype == "PROCEEDINGS":
            entry = Proceedings(fname, entryname)
        elif entrytype == "MISC":
            entry = Misc(fname, entryname)
        else:
            entry = Misc(fname, entryname)

        OrigBibEntries[entryname] = entries[i][2]

        pattern = r"(\w+)\s*=\s*([^\*]+)\*?"
        all_keys = re.findall(pattern, entries[i][2])

        for key, value in all_keys:
            key = key.strip().upper()
            # remove leading and ending braces or quotes
            value = value.strip().strip("{}").strip('"')
            # remove embedded braces unless escaped
            value = re.sub(r'(?<!\\\\)[{}]', '', value)
            # remove escapes
            value = value.replace("\\", "")
            entry.values[key] = value

        BibEntries[fname].append(entry)


def parse_bib(bib_file, bib_file_string):
    DELIM = "*"
    count = 0
    bib_file_string = list(bib_file_string)

    for i in range(len(bib_file_string)):
        if bib_file_string[i] == "{":
            if count == 0:
                bib_file_string[i] = DELIM
            count += 1
        elif bib_file_string[i] == "}":
            count -= 1
            if count == 0:
                bib_file_string[i] = DELIM
        elif bib_file_string[i] == "," and count == 1:
            bib_file_string[i] = DELIM
        elif bib_file_string[i] == "\r" and count == 1:
            bib_file_string[i] = DELIM

    bib_file_string = "".join(bib_file_string).replace("**", DELIM)
    nb_bib_entry = bib_file_string.count("@")
    matches = []

    for match in re.finditer(r"@(\w+)\s*\*\s*([^\*]*)\*([^@]+)\*", bib_file_string):
        entry_type = match.group(1)
        entry_name = match.group(2)
        entry_contents = match.group(3)
        matches.append((entry_type, entry_name, entry_contents))

    parse_entries(bib_file, matches)


def parse_bib_file(bib_file):
    if bib_file not in BibEntries:
        if not os.path.exists(bib_file):
            return False

        with open(bib_file, "r") as f:
            bib_file_string = f.read()

        bib_file_string = bib_file_string.replace("\n", "")
        parse_bib(bib_file, bib_file_string)
    return BibEntries

class BibtexEntry:
    def __init__(self, bibfile, entryname):
        self.values = {}
        self.bibfile = bibfile
        self.entryname = entryname
        self.entrytype = ""

    def eval_cond(self, cond):
        to_eval = "(" + cond + ")"
        to_eval = to_eval.replace("&gt;", ">")
#        to_eval = to_eval.replace("*", ",")   
        return eval(to_eval)

    def eval_get(self, get):
        get = get.replace("\\\"", "\"")
        get = get.replace("&gt;", ">")
#        get = get.replace("*", ",")
        return eval(get)

    def short_month(self, month):
        months = {
            "jan": "January", "feb": "February", "mar": "March", "apr": "April", "may": "May",
            "jun": "June", "jul": "July", "aug": "August", "sep": "September", "oct": "October",
            "nov": "November", "dec": "December"
        }
        return months.get(month, month)

    def get_authors(self):
        authors = self.get('AUTHOR')
        if not authors:
            return False
        authors = authors.split(" and ")

        ret = ""
        for i, author in enumerate(authors):
            ret += author
            if i == len(authors) - 2:
                if (len(authors) == 2):
                    # no comma if only 2 authors
                    ret += " and "
                else:
                    ret += ", and "
            elif i < len(authors) - 2:
                ret += ", "
        return ret

    def get_editors(self):
        editors = self.get('EDITOR')
        if not editors:
            return False
        editors = editors.split(" and ")

        ret = ""
        for i, editor in enumerate(editors):
            ret += editor
            if i == len(editors) - 2:
                if (len(editors) == 2):
                    # no comma if only 2 editors
                    ret += " and "
                else:
                    ret += ", and "
            elif i < len(editors) - 2:
                ret += ", "
        return ret

    def get_name(self):
        return self.entryname

    def get_title(self):
        return self.get_format('TITLE')

    def get_abstract(self):
        return self.get('ABSTRACT')

    def get_comment(self):
        return self.get('COMMENT')

    def get_pages(self):
        pages = self.get('PAGES')
        if pages:
            found = pages.find("--")
            if found != -1:
                return pages.replace("--", "-")
            else:
                return pages
        return ""

    def get_pages_with_label(self):
        pages = self.get_pages()
        if pages:
            if pages[0].isdigit() and pages.find("-") != -1:
                return "pp. " + pages
            elif pages[0].isdigit():
                return "p. " + pages
        return pages

    def get(self, field):
        if field not in self.values:
            field = field.lower()
            if field not in self.values:
                return False
        return self.values[field].strip()

    def get_format(self, field):
        ret = self.get(field)
        if ret:
            ret = ret.replace("{", "").replace("}", "")
        return ret

    def get_complete_entry_url(self):
        global BibtexCompleteEntriesUrl

        bibfile = self.bibfile
        entryname = self.entryname

        if entryname != " ":
            if BibtexCompleteEntriesUrl == "":
                BibtexCompleteEntriesUrl = "/publications/bibtex#\$Entryname"

            ret_url = BibtexCompleteEntriesUrl.replace('\$Bibfile', bibfile).replace('\$Entryname', entryname)
        return ret_url

    def get_pre_string(self):
        global BibtexLang
        ret = ""

        lang = self.get("LANG")
        if lang and lang in BibtexLang:
            ret += BibtexLang[lang]

        author = self.get_authors()
        if author:
            ret += author

        title = self.get_title()
        if title:
            ret += f', "**{title}**'
            if len(ret) > 2:
                ret += ","
            ret += '"'

        return ret

    def get_post_string(self, dobibtex=True):
        global ScriptUrl, BibtexUrlLink, BibtexBibLink, BibtexBibtexButton, BibtexPreprintLink
        global BibtexLinkIcon, BibtexSlideshare, BibtexPdfIcon, pagename, TitleLinkDOIURL

        ret = "."

        award = self.get("AWARD")
        if award:
            ret += f" ***{award} Award***. "

        if not TitleLinkDOIURL:
            url = self.get("URL")
            if url:
                ret += f" <a href='{url}' target='_blank'>{BibtexLinkIcon}</a>"

            doi = self.get("DOI")
            if doi:
		        # if DOI is just the number, append http://dx.doi.org/
                if (not "http://" in doi and not "https://" in doi):
                    doi = "https://dx.doi.org/" + doi
                ret += f" <a href='{doi}' target='_blank'>{BibtexDoiIcon}</a>"

        if self.entryname != " ":
            poster = self.get("POSTER")
            if poster:
                ret += f" <a href='{poster}' target='_blank'>{BibtexPosterLink}</a>"

            preprint = self.get("PREPRINT")
            if preprint:
                ret += f" <a href='{preprint}' target='_blank'>{BibtexPdfIcon}</a>"

            pdf = self.get("PDF")
            if pdf:
                ret += f" <a href='{pdf}' target='_blank'>{BibtexPdfIcon}</a>"

            arxiv = self.get("ARXIV")
            if arxiv:
                ret += f" &nbsp;<a href='{arxiv}' target='_blank' {BibtexArxivButton}</a>"

            slides = self.get("SLIDES")
            if slides:
                ret += f" <a href='{slides}' target='_blank' {BibtexSlideshareButton}</a>"

            tripreport = self.get("TRIPREPORT")
            if tripreport:
                ret += f" <a href='{tripreport}' target='_blank'>{BibtexTripIcon}</a>"

            if dobibtex:
                ret += f" &nbsp;<a href='{self.get_complete_entry_url()}' target='_blank' {BibtexBibtexButton}</a>"

        return ret

    def cite(self):
        ret = f"[{self.entryname}]({self.get_complete_entry_url()})"
        return ret

    def get_bib_entry(self):
        global OrigBibEntries, BibtexSilentFields, BibtexGenerateDefaultUrlField
        INDENT = "    "

        ret = f"{{% raw %}}\n\n```bibtex\n@{self.entrytype} {{{self.entryname},\n{INDENT}"

        one_bib_entry = OrigBibEntries[self.entryname]
        commas_bib_entry = one_bib_entry.replace("*", f",\n{INDENT}")
        ret += commas_bib_entry
        ret += "\n}\n```\n\n{% endraw %}\n\n"
        return ret

    def get_complete_entry(self):
        ret = f"## [{self.entryname}](#{self.entryname})\n\n"
        ret += self.get_summary(False)

        abstract = self.get_abstract()
        if abstract:
            ret += f"\n\n**Abstract:**\n\n   {abstract}"
        comment = self.get_comment()
        if comment:
            ret += f"\n\n**Comment:**\n\n   {comment}"

        ret += f"\n\n[](#" + self.entryname + "Bib)\n"
        ret += f"**BibTeX entry:**\n\n" + self.get_bib_entry()
        return ret

    def get_sole_page_entry(self):
        ret = f"!{self.entryname}\n"
        ret += f"\n!!!Summary\n"
        ret += self.get_pre_string() + "\n"

        abstract = self.get_abstract()
        if abstract:
            ret += f"\n!!!Abstract\n{abstract}\n"

        comment = self.get_comment()
        if comment:
            ret += f"\n!!!Comment\n{comment}\n"

        ret += f"[[#" + self.entryname + "Bib]]\n"
        ret += f"\n!!!Bibtex entry\n" + self.get_bib_entry() + "\n"
        return ret

class Book(BibtexEntry):
    def __init__(self, bibfile, entryname):
        super().__init__(bibfile, entryname)
        self.entrytype = "BOOK"

    def get_summary(self, dobibtex=True):
        ret = ""
        global TitleLinkDOIURL

        author = self.get_authors()
        editor = self.get_editors()

        if author:
            ret = author
        elif editor:
            ret = editor + ", Eds."

        title = self.get("TITLE")
        if title:
            if TitleLinkDOIURL:
                doi = self.get("DOI")
                url = self.get("URL")
                if doi:
                    # if DOI is just the number, append http://dx.doi.org/
                    if (not "https://" in doi and not "http://" in doi):
                        doi = "https://dx.doi.org/" + doi
                    ret += f", [*{title}*]({doi})"
                elif url:
                    ret += f", [*{title}*]({url})"
                else:
                    ret += f", *{title}*"
            else:
                ret += f", *{title}*"

            if len(ret) > 2 and ret[-1] != '?':
                ret += ","

        if editor and author:
            ret += f" ({editor}, Eds.)"

        publisher = self.get("PUBLISHER")
        if publisher:
            ret += f" {publisher}"

        address = self.get("ADDRESS")
        if address:
            if ret and ret[-1] != "." and ret[-1] != "'":
                ret += ","
            ret += f" {address}"

        year = self.get("YEAR")
        if year:
            ret += f", {year}"

        if ret and ret[-3] == '.':
            ret = ret[: -3] + ret[-2:]

        post = super().get_post_string(dobibtex)
        ret += post

        return ret


class InBook(BibtexEntry):
    def __init__(self, bibfile, entryname):
        super().__init__(bibfile, entryname)
        self.entrytype = "INBOOK"

    def get_summary(self, dobibtex=True):
        ret = super().get_pre_string()
        booktitle = super().get("BOOKTITLE")
        if booktitle:
            ret += f" In *{booktitle}*."

            editor = super().get("EDITOR")
            if editor:
                if ret[-1] != '.':
                    ret += "."
                ret += f" ({editor}, Eds.)"

            address = super().get("ADDRESS")
            if address:
                if ret[-1] != '.':
                    ret += "."
                ret += f" {address}"

            publisher = super().get("PUBLISHER")
            if publisher:
                if ret[-1] != ',':
                    ret += ","
                ret += f" {publisher}"

            year = self.get("YEAR")
            if year:
                ret += f", {year}"

            pages = self.get_pages_with_label()
            if pages:
                if ret[-1] != ')':
                    ret += ","
                elif pages[0] == 'p':
                    pages = "P" + pages[1:]
                ret += f" {pages}"

            organization = super().get("ORGANIZATION")
            if organization:
                if ret[-1] != ')':
                    ret += ", "
                ret += f". {organization}"

        return ret + super().get_post_string(dobibtex)


class Proceedings(BibtexEntry):
    def __init__(self, bibfile, entryname):
        super().__init__(bibfile, entryname)
        self.entrytype = "PROCEEDINGS"

    def get_summary(self, dobibtex=True):
        ret = super().get_pre_string()
        editor = super().get("EDITOR")
        if editor:
            ret += f" ({editor}, Eds.)"

        volume = super().get("VOLUME")
        if volume:
            ret += f" volume {volume}"
            series = super().get("SERIES")
            if series:
                ret += f" of *{series}*"

        address = super().get("ADDRESS")
        if address:
            ret += f", {address}"

        orga = super().get("ORGANIZATION")
        if orga:
            ret += f", {orga}"

        publisher = super().get("PUBLISHER")
        if publisher:
            ret += f", {publisher}"

        ret += super().get_post_string(dobibtex)
        return ret


class Misc(BibtexEntry):
    def __init__(self, bibfile, entryname):
        super().__init__(bibfile, entryname)
        self.entrytype = "MISC"

    def get_summary(self, dobibtex=True):
        ret = super().get_pre_string()

        howpublished = super().get("HOWPUBLISHED")
        if howpublished:
            ret += f" {howpublished}"

        month = super().get("MONTH")
        if month:
            month = super().short_month(month)
            if ret[-1] != '.':
                ret += ","
            ret += f" {month}"

        year = self.get("YEAR")
        if year:
            ret += f" {year}"

        ret += super().get_post_string(dobibtex)
        return ret

class Article(BibtexEntry):
    def __init__(self, bibfile, entryname):
        super().__init__(bibfile, entryname)
        self.entrytype = "ARTICLE"

    def get_summary(self, dobibtex=True):
        ret = super().get_pre_string()
        journal = super().get("JOURNAL")
        if journal:
            ret += " *" + journal + "*"
            volume = super().get("VOLUME")
            if volume:
                ret += ", Vol. " + volume
            number = super().get("NUMBER")
            if number:
                ret += ", No. " + number

            month = super().get("MONTH")
            if month:
                month = super().short_month(month)  
                if ret[-1] != '.':
                    ret += ","
                ret += " " + month

            year = super().get("YEAR")
            if year:
                if month:
                    ret += " " + year
                else:
                    if ret[-1] != '.':
                        ret += ","
                    ret += " " + year

            pages = super().get_pages_with_label()
            if pages:
                ret += ", " + pages

        return ret + super().get_post_string(dobibtex)


class InProceedings(BibtexEntry):
    def __init__(self, bibfile, entryname):
        super().__init__(bibfile, entryname)
        self.entrytype = "INPROCEEDINGS"

    def get_summary(self, dobibtex=True):
        ret = super().get_pre_string()
        booktitle = super().get("BOOKTITLE")

        if booktitle:
            ret += " In *" + booktitle + "*."

            address = super().get("ADDRESS")
            if address:
                if ret[-1] != '.':
                    ret += "."
                ret += " " + address

            month = super().get("MONTH")
            if month:
                month = super().short_month(month)  
                if ret[-1] != '.':
                    ret += ","
                ret += " " + month

            year = super().get("YEAR")
            if year:
                ret += " " + year

            editor = super().get("EDITOR")
            if editor:
                if ret[-1] != '.':
                    ret += "."
                ret += " (" + editor + ", Eds.)"

            publisher = super().get("PUBLISHER")
            if publisher:
                if ret[-1] != ')':
                    ret += "."
                ret += " " + publisher

            pages = super().get_pages_with_label()
            if pages:
                if ret[-1] != ')':
                    ret += ","
                elif pages[0] == 'p':
                    pages = "P" + pages[1:]
                ret += " " + pages

            note = super().get("NOTE")
            if note:
                if ret[-1] != ')':
                    ret += ", "
                ret += note

        return ret + super().get_post_string(dobibtex)


class InCollection(BibtexEntry):
    def __init__(self, bibfile, entryname):
        super().__init__(bibfile, entryname)
        self.entrytype = "INCOLLECTION"

    def get_summary(self, dobibtex=True):
        ret = super().get_pre_string()
        booktitle = super().get("BOOKTITLE")
        if booktitle:
            ret += " In *" + booktitle + "*"

            editor = super().get_editors()
            if editor:
                ret += ", " + editor + ", Eds."

            pages = super().get_pages_with_label()
            if pages:
                ret += ", " + pages + ". "

            publisher = super().get("PUBLISHER")
            if publisher:
                ret += publisher

            year = super().get("YEAR")
            if year:
                ret += ", " + year

        return ret + super().get_post_string(dobibtex)

class MasterThesis(BibtexEntry):
    def __init__(self, bibfile, entryname):
        super().__init__(bibfile, entryname)
        self.entrytype = "MASTERSTHESIS"

    def get_summary(self, dobibtex=True):
        ret = super().get_pre_string()
        ret += " Master's thesis"
        school = super().get("SCHOOL")
        if school:
            ret += ", *" + school + "*"

        month = super().get("MONTH")
        if month:
            month = super().short_month(month)  
            if ret[-1] != '.':
                ret += ","
            ret += " " + month

        year = super().get("YEAR")
        if year:
            ret += " " + year

        return ret + super().get_post_string(dobibtex)


class TechReport(BibtexEntry):
    def __init__(self, bibfile, entryname):
        super().__init__(bibfile, entryname)
        self.entrytype = "TECHREPORT"

    def get_summary(self, dobibtex=True):
        ret = super().get_pre_string()
        type_value = super().get("TYPE")
        if type_value:
            ret += " " + type_value
        else:
            ret += " Technical report"

        number = super().get("NUMBER")
        if number:
            ret += " " + number

        institution = super().get("INSTITUTION")
        if institution:
            ret += ", " + institution

        month = super().get("MONTH")
        if month:
            month = super().short_month(month)  
            if ret[-1] != '.':
                ret += ","
            ret += " " + month

        year = super().get("YEAR")
        if year:
            ret += " " + year

        return ret + super().get_post_string(dobibtex)

class PhdThesis(BibtexEntry):
    def __init__(self, bibfile, entryname):
        super().__init__(bibfile, entryname)
        self.entrytype = "PHDTHESIS"

    def get_summary(self, dobibtex=True):
        ret = super().get_pre_string()
        ret += " PhD thesis"
        school = super().get("SCHOOL")
        if school:
            ret += ", *" + school + "*"

        month = super().get("MONTH")
        if month:
            month = super().short_month(month)  
            if ret[-1] != '.':
                ret += ","
            ret += " " + month

        year = super().get("YEAR")
        if year:
            ret += " " + year

        return ret + super().get_post_string(dobibtex)
