<?php
   
    namespace Itpathsolutions\Chatbot;
    use Illuminate\Support\ServiceProvider;
    class ChatbotServiceProvider extends ServiceProvider {
        public function boot()
        {
            $this->loadRoutesFrom(__DIR__.'/routes/web.php');
            $this->loadViewsFrom(__DIR__.'/resources/views', 'chatbot');
            $this->mergeConfigFrom(
                __DIR__.'/Config/config.php', 'chatbot'
            );
    
            // Publish configuration file to the application's config directory
            $this->publishes([
                __DIR__.'/Config/config.php' => config_path('chatbot.php'),
            ]);
        }
        public function register()
        {
      }
   }
?>