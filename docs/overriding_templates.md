Overriding Default ReconnixUserBundle Templates
==========================================

### Note: 

The ReconnixUserBundle provides some extra routes, and therefore some extra twig files, on top of the FOSUserBundle. To see the names of these routes, and their paths, run:

```bash
$ php app/console router:debug
```

You will see the routes:

`/users`
`/users/view`
`/users/new`

Each of these have Twig templates that can be overriden. They each extend the basic `layout.html.twig` file provided by FOSUserBundle. To go a level deeper and override that, and all other FOS routes, see [here](https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/doc/overriding_templates.md). This page simply describes overriding specific ReconnixUserBundle pages.




### Example: Overriding the template a `/users`

To override any template provided by a third party bundle, you create a new directory structure in your project which maps the structure that the bundle uses. For example, the `/users` page uses a Twig file at `Reconnix/UserBundle/Resources/views/All/index.html.twig`. To override this, we need to create the following file `app/Resources/ReconnixUserBundle/**views/All/index.html.twig**`. 

Notice that this is in the top level `app` directory, not specific to a bundle. And notice that the directory structure is mirrored from the `view` directory onwards.

A simple override of this page would look like this:

``` html+jinja
{% extends 'FOSUserBundle::layout.html.twig' %}

{% block fos_user_content %}
    SOME CUSTOM TEXT
    <ul class="custom-class-name">
    {% for user in users %}
        <li>
            <a href="{{path('reconnix_user_view_index', {'id':user.id})}}">{{user.username}}</a>
        </li>
    {% endfor %}
    </ul>

    <a href="{{path('reconnix_user_new_index')}}">Add new User</a>
{% endblock fos_user_content %}
```

The important points are that it extends the FOSUserBundle `layout.html.twig`, and that the content is placed within the `fos_user_content` block. 

If you override the FOSUserBundle `layout.html.twig` file as described [here](https://github.com/FriendsOfSymfony/FOSUserBundle/blob/master/Resources/doc/overriding_templates.md), remember to reference the new `layout.html.twig` in your override files.