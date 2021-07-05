<?php
/* bib2md.php
* Generate Jekyll-compatable .md files per year from BibTeX file
* Based on BibtexRef plugin for PmWiki, https://www.pmwiki.org/wiki/Cookbook/BibtexRef
* Modified to generate Markdown - Michele Weigle, July 2021 
*/
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
   
    include 'bibtexref3-md.php';				
   
    $bibTexFile = 'mweigle.bib';

    $years = array ("2006", "2007", "2008", "2009", "2010", "2011",
        "2012", "2013", "2014", "2015", "2016", "2017", "2018", "2019", 
        "2020", "2021");

    foreach ($years as $year) {
        $outfile = $year . ".md";

        $fp = fopen($outfile, "w") or die("Unable to open file!");

        fwrite($fp, "---\n");
        fwrite($fp, "title: \"" . $year . "\"\n");
        fwrite($fp, "collection: publications\n");
        fwrite($fp, "permalink: /publications/" . $year . "\n");
        fwrite($fp, "---\n");

        $paper_string = BibQuery($bibTexFile, "(\$this->get('YEAR') == $year)", "!\$this->get('PUBDATE')", "100");
        fwrite($fp, $paper_string);
        fclose($fp);
    }
 
    /* Other available commands:

    $reftag = "jones-websci21";
    CompleteBibEntry($bibTexFile, $reftag)
    BibSummary($bibTexFile, $reftag)
    BibCite($bibTexFile, $reftag)) 

    // BibQuery('Main.Bibtex', '($this->get('PUBDATE') > 202008)', '!$this->get('PUBDATE')', '100')
    // BibQuery('Main.bibtex', 'strpos($this->entrytype,'BOOK')!==FALSE', '!$this->get('PUBDATE')', '100')
    // BibQuery('Main.bibtex', 'strpos($this->entrytype,'MISC')!== FALSE || strpos($this->entrytype,'TECHREPORT')!==FALSE || strpos($this->entrytype,'PHDTHESIS')!==FALSE ', '!$this->get('PUBDATE')', '100')
    */

?>

