*Edits by me (weiglemc) below.  For [original README](https://github.com/academicpages/academicpages.github.io/blob/master/README.md) see [academicpages.github.io](https://github.com/academicpages/academicpages.github.io).*

A Github Pages template for academic websites. This was forked (then detached) by [Stuart Geiger](https://github.com/staeiou) from the [Minimal Mistakes Jekyll Theme](https://mmistakes.github.io/minimal-mistakes/), which is © 2016 Michael Rose and released under the MIT License. See LICENSE.md.

## Setup and Customization Guides

*Much thanks to [Rob Williams](https://jayrobwilliams.com) for his setup posts!*

* [Building an Academic Website](https://jayrobwilliams.com/posts/2020/06/academic-website/)
* [Customizing an Academic Website](https://jayrobwilliams.com/posts/2020/07/customizing-website)
* [Adding Content to an Academic Website](https://jayrobwilliams.com/posts/2020/08/website-content/)
* [Website GitHub repo](https://github.com/jayrobwilliams/jayrobwilliams.github.io)

I used <https://realfavicongenerator.net> to generate the needed favicons (it's a lot more complicated than it used to be...).

## Debugging

If the page doesn't regenerate automatically after a new commit, check for build or deployment errors.  Check the [pages-build-deployment](https://github.com/weiglemc/weiglemc.github.io/actions/workflows/pages/pages-build-deployment) log in the [Actions](https://github.com/weiglemc/weiglemc.github.io/actions) tab.

## Generating Publication Pages

I adapted code from PmWiki's [BibtexRef Cookbook](https://www.pmwiki.org/wiki/Cookbook/BibtexRef) to read in a BibTeX file and generate various Markdown files for the website.  The files are all in the [markdown_generator](markdown_generator/) folder in this repo.

* [`bib2md.php`](markdown_generator/bib2md.php) - driver script to generate Markdown files with publication entries
   * update with input BibTeX file, years of publications to generate, type of publications, and definition of "recent"
   * current version writes files directly into [_publications](_publications/) folder
* [`bib2md-students.php`](markdown_generator/bib2md-students.php) - driver script to generate Markdown file with student PhD dissertations and MS theses
  * update with bibtag for new students
  * I copy/paste lines into [_pages/students.md](_pages/students.md)
* [`bibtexref3-md.php`](markdown_generator/bibtexref3-md.php) - script adapted from PmWiki cookbook to generate Markdown
  * should only need updating to change appearance of reference line output

I execute these locally on a checked out copy of the repo using PHP in a terminal.

## To run locally on MacOS

Note: *see below for installation on an M1 Mac*

1. Install Git and Ruby via the Xcode Command Line Tools by running `xcode-select --install` 
1. Install [Bundler](https://bundler.io/), a package manager for Ruby by running `gem install bundler`
1. Clone the repo and make updates
1. Move to the local cloned directory and run `bundle install` to install ruby dependencies. If you get errors, delete Gemfile.lock and try again.
1. Run `bundle exec jekyll serve` to generate the HTML and serve it from `localhost:4000`. The local server will automatically rebuild and refresh the pages on change.

Installing on M1

* Duplicate the /Applications/Terminal app and rename it Terminal-Rosetta
* Use "Get Info" and check "Open using Rosetta"
* Open this new Terminal app to install Bundler
* Install Bundler with `arch -x86_64 sudo gem install bundle`
* Move to the local cloned directory and install the gems with `arch -x86_64 bundle install`
* If there are errors, delete Gemfile.lock and try `arch -x86_64 bundle install` again
* If that doesn't work, uninstall the offending gem `sudo gem uninstall package_name` and try `arch -x86_64 bundle install` again.
* Make sure to use Terminal-Rosetta when starting the server with `bundle exec jekyll serve`

# Instructions

1. Register a GitHub account if you don't have one and confirm your e-mail (required!)
1. Fork [academicpages repo](https://github.com/academicpages/academicpages.github.io) by clicking the "fork" button in the top right. 
1. Go to the repository's settings (rightmost item in the tabs that start with "Code", should be below "Unwatch"). Rename the repository "[your GitHub username].github.io", which will also be your website's URL.
1. Set site-wide configuration and create content & metadata (see below -- also see [this set of diffs](http://archive.is/3TPas) showing what files were changed to set up [an example site](https://getorg-testacct.github.io) for a user with the username "getorg-testacct")
1. Upload any files (like PDFs, .zip files, etc.) to the files/ directory. They will appear at https://[your GitHub username].github.io/files/example.pdf.  
1. Check status by going to the repository settings, in the "GitHub pages" section
1. (Optional) Use the Jupyter notebooks or python scripts in the `markdown_generator` folder to generate markdown files for publications and talks from a TSV file.

See more info at https://academicpages.github.io/

# Changelog -- bugfixes and enhancements


To support this, all changes to the underlying code appear as a closed issue with the tag 'code change' -- get the list [here](https://github.com/academicpages/academicpages.github.io/issues?q=is%3Aclosed%20is%3Aissue%20label%3A%22code%20change%22%20). Each issue thread includes a comment linking to the single commit or a diff across multiple commits, so those with forked repositories can easily identify what they need to patch.
