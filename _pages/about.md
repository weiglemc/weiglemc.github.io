---
permalink: /
title: "About Me"
excerpt: ""
author_profile: true
redirect_from: 
  - /about/
  - /about.html
  - /home/
twitter-color: "#55acee"
github-color: "#171516"
blogger-color: "#F37100"
---
I am a Professor of Computer Science at Old Dominion University, where I've been on faculty since 2006.  I'm a graduate of UNC-Chapel Hill (PhD 2003, MS 1998) and Northeast Louisiana University (BS 1996).  I'm currently featured on the [ODU Faculty Women in STEM page](https://www.odu.edu/facultydevelopment/women-in-stem#tab9=3&done1612907281342), where you can read a bit about my background and experiences as a woman in computer science.

**Research Interests:** web science, social media, web archiving, digital preservation, information visualization
<br/>(see some student infovis projects at my [infovis gallery](https://www.cs.odu.edu/~mweigle/research/gallery.html))

For an overview of what I've been working on the past few years, see [On the importance of web archiving](https://items.ssrc.org/parameters/on-the-importance-of-web-archiving/), an article I wrote for [*SSRC Parameters*](https://items.ssrc.org/category/parameters/) in 2018.

## Teaching (Spring 2022)

1. [CS 725/825 - Information Visualization & Data Analytics](/teaching/2022-spr-cs725825) / Tu 4:20-7pm, MGB 0127 and online via Zoom
1. [CS 800 - Research Methods](/teaching/2022-spr-cs800) / TR 11am-12:15pm, GORNT 0205 and online via Zoom

## Research

I'm a member of the ODU Web Science and Digital Libraries (WS-DL) Research Group.  

<a href="https://oduwsdl.github.io/" target="_blank" class="btn btn--mcw"><i class="fas fa-fw fa-link"></i><span> WS-DL Webpage</span></a>
<a href="https://twitter.com/WebSciDL" target="_blank" class="btn btn--mcw"><i class="fab fa-twitter" style="color: {{ page.twitter-color }}"></i><span> WS-DL Twitter</span></a>
<a href="https://ws-dl.blogspot.com/" target="_blank" class="btn btn--mcw"><i class="fab fa-blogger" style="color: {{ page.blogger-color }}"></i><span> WS-DL Blog</span></a>
<a href="https://github.com/oduwsdl" target="_blank" class="btn btn--mcw"><i class="fab fa-fw fa-github" style="color: {{ page.github-color }}"></i><span> WS-DL GitHub</span></a>
{: style="text-align: center;"}

{% for post in site.publications reversed %}
  {% if post.type == "recent" %}
    {% include archive-single.html %}
  {% endif %}
{% endfor %}

## Active Funding

* Sampath Jayarathna (PI), Jian Wu, Senior Personnel: Michele C. Weigle, Michael Nelson, Vikas Ashok, Faryaneh Poursardar, Anne Perrotti (Education), Erika Frydenlund (VMASC), REU Site: Research Experiences for Undergraduates in Disinformation Detection and Analytics, NSF, Mar 2022 - Feb 2025, $324,000.
* Erika Frydenlund (PI), Jose Padilla (VMASC), Michele C. Weigle, Jennifer Fish, Michael L. Nelson, Michaela Hynie (York University, Canada), Hanne Haaland (Univ of Agder, Norway), Hege Wallevik (Univ of Agder, Norway), Katherine Palacio-Salgar (Universidad del Norte, Columbia), [What's Missing? Innovating Interdisciplinary Methods for Hard-to-Reach Environments](https://www.defense.gov/News/Releases/Release/Article/2944623/department-of-defense-awards-287m-in-grants-for-the-fy2021-minerva-research-ini/), Jan 2022 - Dec 2024, Department of Defense Minerva Research Initiative, $1,618,699 - [blog post](https://ws-dl.blogspot.com/2022/03/2022-03-03-whats-missing-innovating.html)
* Michael L. Nelson, Michele C. Weigle, Jian Wu, Web Science and Web Security, COVA CCI Curriculum Development, Jan 2022 - Aug 2022, $10,000
* Michael L. Nelson, Michele C. Weigle, Game Walkthroughs and Web Archiving, IIPC Discretionary Funding Program, Jan 2022 - Dec 2022, $10,000.
* Vicky Rampin (NYU), Martin Klein (LANL), wilkie (Univ. of Pittsburgh), Michael L. Nelson, Michele C. Weigle, [CoSAI - Collaborative Software Archiving for Institutions](https://sloan.org/grant-detail/9628), Alfred P. Sloan Foundation, Sep 2021 - Sep 2023, $520,503.
* Michael L. Nelson, Michele C. Weigle, Sue Kimmel, Jessica Ritchie, and Hongyi Wu, [A Graduate Certificate in Web Archiving](https://www.imls.gov/grants/awarded/re-250048-ols-21), IMLS Laura Bush 21st Century Librarian Program, RE-250048-OLS-21, Aug 2021 - Jul 2022, $98,361.
<!-- * Michael L. Nelson (PI), [Improving the Dark and Stormy Archives Framework by Summarizing the Collections of the National Library of Australia](https://netpreserve.org/projects/dark-and-stormy-archives/), IIPC Discretionary Funding Program, Jan 2021 - Jan 2022, $50,000
* Michele C. Weigle, Michael L. Nelson, Deborah Kempe (Frick Art Reference Library and New York Art Resources Consortium), Pamela Graham (Columbia University Libraries), and Alex Thurman (Columbia University Libraries), [Visualizing Webpage Changes Over Time](https://securegrants.neh.gov/publicquery/main.aspx?f=1&gn=HAA-256368-17), NEH/IMLS Digital Humanities Advancement Grant HAA-256368-17, Oct 2017 – Mar 2020, $75,000 - [blog post](https://ws-dl.blogspot.com/2017/10/2017-10-16-visualizing-webpage-changes.html)
* Michael L. Nelson, Michele C. Weigle, and Herbert Van de Sompel (LANL), [Preservation of Scholarly Record on the Web](https://mellon.org/grants/grants-database/grants/old-dominion-university/11600663/), Andrew W. Mellon Foundation 11600663, Apr 2016 - Mar 2020, $830,000.
-->
*My full funding list is available in my [CV](https://weiglemc.github.io/cv/).*

## Service

* Editorial Board
  * [*Journal of the Association for Information Science and Technology*](https://asistdl.onlinelibrary.wiley.com/hub/journal/23301643/homepage/editorialboard) (JASIST) (2016-present)
  * [*International Journal on Digital Libraries*](https://www.springer.com/computer/database+management+&+information+retrieval/journal/799/PS2?detailsPage=editorialBoard) (IJDL) (2018-present)
* Steering Committee Member, ACM/IEEE Joint Conference on Digital Libraries (JCDL) (2020-present)
* Doctoral Consortium Co-Chair, ACM/IEEE Joint Conference on Digital Libraries (JCDL), 2022
* Program Co-Chair, ACM/IEEE Joint Conference on Digital Libraries (JCDL), 2016
* Faculty Advisor, ACM-W @ ODU
* Faculty Representative, [NCWIT Academic Alliance](https://www.ncwit.org/alliances/aa)
* ODU-CS Graduate Program Director, 2013-2019


## Bio

Dr. Michele C. Weigle is a Professor of Computer Science at Old Dominion University. Her research interests include web science, digital preservation, and information visualization. Her research in web archiving includes developing tools for personal web archiving, improving access to web archives for general users and researchers, and applying information visualization to web archives. She has published over 115 articles in peer-reviewed conferences and journals and has served as PI or Co-PI on external research grants totaling over $3.7M from the National Science Foundation, the National Endowment for the Humanities, the Institute of Museum and Library Services, and the Andrew W. Mellon Foundation. She is currently serving on the editorial boards of the *Journal of the Association for Information Science and Technology* (JASIST) and the *International Journal on Digital Libraries* (IJDL). Dr. Weigle received her PhD in computer science from the University of North Carolina in 2003. From 2004-2006, she was an Assistant Professor in the Department of Computer Science at Clemson University. She joined ODU in 2006.

### Academic Timeline

* July 2018 - present, Professor, [Computer Science](https://www.cs.odu.edu/), [Old Dominion University](https://www.odu.edu/)
* July 2012 - July 2018, Associate Professor, Computer Science, Old Dominion University
* July 2006 - July 2012, Assistant Professor, Computer Science, Old Dominion University
* July 2004 - July 2006, Assistant Professor, [Computer Science](http://www.clemson.edu/ces/departments/computing/), [Clemson University](http://www.clemson.edu)
* August 2003 - June 2004, Visiting Assistant Professor, Computer Science, University of North Carolina
* August 2003, Ph.D., [Computer Science](https://www.cs.unc.edu), [University of North Carolina](https://www.unc.edu)
* May 1998, M.S., Computer Science, University of North Carolina
* May 1996, B.S., [Computer Science](http://www.ulm.edu/cba/computerscience/index.html) ([Honors Program](http://www.ulm.edu/honors)), Northeast Louisiana University (now [UL-Monroe](http://www.ulm.edu))
  
