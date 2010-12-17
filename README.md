Provdes an easy way to add Markdown to your symfony project.

Using the markdown and smartypants libraries from Michel Fortin, and the MarkItUp editor,
I have made an easy to use widget and library for using Markdown content in Symfony.

It assumes majaxJqueryPlugin is loaded (or some form of jQuery 1.4.x and jQuery UI 1.8.x)

* PHP Markdown: http://michelf.com/projects/php-markdown/
* PHP Smartypants: http://michelf.com/projects/php-smartypants/
* MarkItUp: http://markitup.jaysalvat.com/home/
* majaxJqueryPlugin: https://github.com/jmather/majaxJqueryPlugin

##Basic Setup

Enable the majaxMarkdown module in your application's settings.yml

To use the widget in your Form class:

    class ... snip

      public function configure()
      {
        // ... snip ...

        $parameters = array();
        $attributes = array('style' => 'height: 100px;');
        $this->setWidget('content', new majaxWidgetFormMarkdownEditor($parameters, $attributes));

        // ... snip ...
      }
    }




To render the content:

      echo majaxMarkdown::transform($markdown_content);


You can control it with options in your app.yml:


    all:
      majaxMarkdown:
        style: markdown_extra # choose markdown or markdown_extra
        smartypants_enabled: true # true / false
        smartypants_style: smartypants_typographer # choose smartypants or smartypants_typographer
        smartypants_options: 1 # You'll want to check the smartypants docs for more info on this...
        post_render: false
        post_preview: false


##Adding Post Renderers

You can post-process both the generated text, and also only the preview text, by supplying what function to
run to app_majaxMedia_post_render or app_majaxMedia_post_preview like so:

     sfConfig::set('app_majaxMedia_post_render', array('className', 'static_function'));

or

    sfConfig::set('app_majaxMedia_post_preview', 'some_function_name');
