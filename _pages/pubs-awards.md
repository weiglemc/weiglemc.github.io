---
layout: archive
title: "Award Publications"
permalink: /publications/pubs-awards
author_profile: true
redirect_from: 
  - /awards.md
---

{% if page.author and site.data.authors[page.author] %}
  {% assign author = site.data.authors[page.author] %}{% else %}{% assign author = site.author %}
{% endif %}

{% if author.googlescholar %}
  You can also find my publications on <a href="{{author.googlescholar}}" target="_blank">my Google Scholar profile</a>.
{% endif %}

{% include base_path %}

[All Pubs Sorted by Year](/publications/pubsbyyear), [All Pubs Sorted by Type](/publications/pubsbytype), [External Publication Lists](/publications/lists)

{% for post in site.publications %}
  {% if post.type == "award" %}
    {% include archive-single.html %}
  {% endif %}
{% endfor %}
