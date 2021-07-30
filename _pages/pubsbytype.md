---
layout: archive
title: "Publications By Type"
permalink: /publications/pubsbytype
author_profile: true
redirect_from: 
  - /pubsbytype.md
---

{% if page.author and site.data.authors[page.author] %}
  {% assign author = site.data.authors[page.author] %}{% else %}{% assign author = site.author %}
{% endif %}

{% if author.googlescholar %}
  You can also find my publications on <a href="{{author.googlescholar}}" target="_blank">my Google Scholar profile</a>.
{% endif %}

{% include base_path %}

[Sorted by Year](/publications/pubsbyyear), [Award Publications](/publications/pubs-awards), [External Publication Lists](/publications/lists)

{% for post in site.publications %}
  {% if post.type == "type" %}
    {% include archive-single.html %}
  {% endif %}
{% endfor %}
