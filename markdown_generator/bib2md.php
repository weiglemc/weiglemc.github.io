<?php
/* bib2md.php
* Generate Jekyll-compatable .md files from BibTeX file
*   - one file per year
*   - one file per type of publication
*   - single file with full BibTex entries and anchor ids for linking
*   - single file with recent publications (since 2020)
*   - single file with award publications (look for keyword=award)
* Based on BibtexRef plugin for PmWiki, https://www.pmwiki.org/wiki/Cookbook/BibtexRef
*   - modified to generate Markdown - Michele Weigle, July 2021 
* Example commands (from bibtexref3-md.php):
    $reftag = "jones-websci21";
    CompleteBibEntry($bibTexFile, $reftag);
    BibSummary($bibTexFile, $reftag);
    BibCite($bibTexFile, $reftag);
*/
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
   
    include 'bibtexref3-md.php';				
   
    $bibTexFile = 'mweigle.bib';
    $outputDir = '../_publications';

    $common_preamble = "collection: 'publications'\ndoi-color: '#fcab22'\nacrobat-color: '#f70e0c'\nblogger-color: '#F37100'\n";

    // generate one file per year of publications
    $years = range("1997", "2021");
    foreach ($years as $year) {
        if ($year == "1998" || $year == "2002") continue;  // MCW: I DIDN'T HAVE PUBS THESE YEARS
        $outfile = $year . ".md";
        $fp = fopen("$outputDir/$outfile", "w") or die("Unable to open file!");
        fwrite($fp, "---\n");
        fwrite($fp, "title: \"" . $year . "\"\n");
        fwrite($fp, "type: 'year'\n");
        fwrite($fp, "permalink: /publications/" . $year . "\n");
        fwrite($fp, $common_preamble);
        fwrite($fp, "---\n");
        // BibQuery(bibtex_filename, filter_condition, sort_condition, max_results)
        $paper_string = BibQuery($bibTexFile, "(\$this->get('YEAR') == $year)", "!\$this->get('PUBDATE')", "100");
        fwrite($fp, $paper_string);
        fclose($fp);
    }

    // generate one file per type of publication
    $types = array("book", "journal", "conference", "techreport", "other");
    foreach ($types as $type) {
        $outfile = $type . ".md";
        $fp = fopen("$outputDir/$outfile", "w") or die("Unable to open file!");
        fwrite($fp, "---\n");
        switch ($type) {
            case "book":
                fwrite($fp, "title: \"Books and Book Chapters\"\n");
                $paper_string = BibQuery($bibTexFile, "strpos(\$this->entrytype,'BOOK')!==FALSE || strpos(\$this->entrytype,'INCOLLECTION')!==FALSE", "!\$this->get('PUBDATE')", "100");
                break;
            case "journal":
                fwrite($fp, "title: \"Journals and Magazines\"\n");
                $paper_string = BibQuery($bibTexFile, "strpos(\$this->entrytype,'ARTICLE')!==FALSE", "!\$this->get('PUBDATE')", "100");
                break;
            case "conference":
                fwrite($fp, "title: \"Conferences and Workshops (Peer-Reviewed)\"\n");
                $paper_string = BibQuery($bibTexFile, "strpos(\$this->entrytype,'INPROCEEDINGS')!==FALSE", "!\$this->get('PUBDATE')", "100");
                break;
            case "techreport":
                fwrite($fp, "title: \"Tech Reports\"\n");
                $paper_string = BibQuery($bibTexFile, "strpos(\$this->entrytype,'TECHREPORT')!==FALSE", "!\$this->get('PUBDATE')", "100");
                break;
            case "other":
                fwrite($fp, "title: \"Other (Poster Presentations, Dissertation, Misc)\"\n");
                $paper_string = BibQuery($bibTexFile, "strpos(\$this->entrytype,'MISC')!==FALSE || strpos(\$this->entrytype,'PHDTHESIS')!==FALSE", "!\$this->get('PUBDATE')", "100");
                break;
        }
        fwrite($fp, "type: 'type'\n");
        fwrite($fp, "permalink: /publications/" . $type . "\n");
        fwrite($fp, $common_preamble);
        fwrite($fp, "---\n");
        fwrite($fp, $paper_string);
        fclose($fp);
    }

    // generate single file with full BibTeX entry for all
    $outfile = "bibtex.md";
    $fp = fopen("$outputDir/$outfile", "w") or die("Unable to open file!");
    fwrite($fp, "---\n");
    fwrite($fp, "title: BibTeX\n");
    fwrite($fp, "type: 'bibtex'\n");
    fwrite($fp, "permalink: /publications/bibtex\n");
    fwrite($fp, $common_preamble);
    fwrite($fp, "---\n");

    $bibentries = ParseBibFile($bibTexFile);
    foreach ($bibentries as $entry) {
        foreach ($entry as $bib) {
            // get ref for each $bib
            $ref = $bib->entryname;
            $paper_string = CompleteBibEntry($bibTexFile, $ref);
            fwrite($fp, $paper_string);
        }
    }
    fclose($fp);

    // generate single file with recent publications (since $year)
    $outfile = "recent.md";
    $year = "2020";
    $fp = fopen("$outputDir/$outfile", "w") or die("Unable to open file!");
    fwrite($fp, "---\n");
    fwrite($fp, "title: \"Recent Publications and Talks\"\n");
    fwrite($fp, "type: 'recent'\n");
    fwrite($fp, "permalink: /publications/recent\n");
    fwrite($fp, $common_preamble);
    fwrite($fp, "---\n");
    // set a max of 10 recent papers
    $paper_string = BibQuery($bibTexFile, "(\$this->get('YEAR') >= \"$year\")", "!\$this->get('PUBDATE')", "10");
    fwrite($fp, $paper_string);
    fclose($fp);

    // generate single file with award publications
    $outfile = "award.md";
    $fp = fopen("$outputDir/$outfile", "w") or die("Unable to open file!");
    fwrite($fp, "---\n");
    fwrite($fp, "title: \"Award Publications\"\n");
    fwrite($fp, "type: 'award'\n");
    fwrite($fp, "permalink: /publications/award\n");
    fwrite($fp, $common_preamble);
    fwrite($fp, "---\n");
    $paper_string = BibQuery($bibTexFile, "(\$this->get('KEYWORD') == 'award')", "!\$this->get('PUBDATE')", "100");
    fwrite($fp, $paper_string);
    fclose($fp);
?>

