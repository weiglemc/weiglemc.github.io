---
layout: archive
title: "Publications"
permalink: /publications/
author_profile: true
---

{% if page.author and site.data.authors[page.author] %}
  {% assign author = site.data.authors[page.author] %}{% else %}{% assign author = site.author %}
{% endif %}

{% if author.googlescholar %}
  You can also find my publications on <a href="{{author.googlescholar}}" target="_blank">my Google Scholar profile</a>.
{% endif %}

{% include base_path %}

[Static Publications List](../publications-static/)

*BibTeX links don't work yet...*

| | | | | | | | | [2021](#2021) | [2020](#2020) |
[2019](#2019) | [2018](#2018) | [2017](#2017) | [2016](#2016) | [2015](#2015) | [2014](#2014) | [2013](#2013) | [2012](#2012) | [2011](#2011) | [2010](#2010) |
[2009](#2009) | [2008](#2008) | [2007](#2017) | [2006](#2006) |

{% comment %}
{% for post in site.publications reversed %}
  <a href="#{{post.title}}">{{post.title}}</a>
{% endfor %}
{% endcomment %}

{% for post in site.publications reversed %}
  <a id="{{post.title}}"></a>
  {% include archive-single.html %}
{% endfor %}
