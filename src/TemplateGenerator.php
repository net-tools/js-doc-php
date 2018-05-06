<?php

namespace Nettools\JsDocPhp;






/**
 * Template generator
 */
class TemplateGenerator {
    
    
    /**
     * Write a generated file to disk
     *
     * @param string $f File path, relative to doc root (ie. /myfile.js.html)
     * @param string $output Root folder for doc output ; we ensure in Controller that $output ends with '/'
     * @param string $html Generated doc as html
     */
    protected static function _writeToFile($f, $output, $html)
    {
        $fhandle = fopen($output . str_replace('/', '--', $f), 'w');
        fwrite($fhandle, $html);
        fclose($fhandle);
    }
    
    
    
    /** 
     * Sort data
     * 
     * @param JSObjects\Package[] $packages Array of packages to deep sort
     */
    protected static function _sort(array &$packages)
    {
        $fun = function($a, $b){
                if ( $a->name > $b->name )
                    return 1;
                else if ( $a->name < $b->name )
                    return -1;
                else 
                    return 0;            
            };
        
        
        
        // sorting packages
        usort($packages, $fun);

        
        // sorting packages classes, namespaces and functions
        foreach ( $packages as $p )
        {
            usort($p->classes, $fun);
            usort($p->namespaces, $fun);
            usort($p->functions, $fun);
            
            foreach ( $p->classes as $c )
            {
                usort($c->properties, $fun);
                usort($c->methods, $fun);
            }
            
            foreach ( $p->namespaces as $c )
            {
                usort($c->properties, $fun);
                usort($c->methods, $fun);
            }
        }
    
    }
    
 
    
    /**
     * Convert the packages doc tree to html files
     *
     * @param \Twig_Environment $twig Twig instance
     * @param JSObjects\Package[] $packages Array of packages to output
     * @param JSObjects\JSObject[] $projectClassesNamespaces Array of classes of project (so that we are able to create links)
     * @param string $output Root directory for output folder
     */
    static protected function _outputFiles(\Twig_Environment $twig, array $packages, array $projectClassesNamespaces, $output)
    {
        $template_package = $twig->load('packageFile.html');
        $template_class = $twig->load('classFile.html');
        $template_ns = $twig->load('nsFile.html');
        
        
        // copy stylesheet, awesomplete
        $f = fopen($output . 'styles.css', 'w');
        fwrite($f, file_get_contents(__DIR__ . '/Templates/styles.css'));
        fclose($f);
        $f = fopen($output . 'awesomplete.css', 'w');
        fwrite($f, file_get_contents(__DIR__ . '/Templates/awesomplete.css'));
        fclose($f);
        $f = fopen($output . 'awesomplete.min.js', 'w');
        fwrite($f, file_get_contents(__DIR__ . '/Templates/awesomplete.min.js'));
        fclose($f);
                
        
        // generate packages
        foreach ( $packages as $p )
        {
            /*if ( $p->isEmpty() )
                continue;*/
            
            // output package
            $html = $template_package->render(array(
                    'package' => $p,
                    'classmap' => $projectClassesNamespaces
                ));
            
            self::_writeToFile($p->file . '.html', $output, $html);

            
            // output package classes content
            foreach ( $p->classes as $c )
            {
                $html = $template_class->render(array(
                        'theclass' => $c,
                        'classmap' => $projectClassesNamespaces
                    ));

                self::_writeToFile('class-' . $c->name . '.html', $output, $html);
            }
            
            // output package namespaces content
            foreach ( $p->namespaces as $ns )
            {
                $html = $template_ns->render(array(
                        'ns' => $ns,
                        'classmap' => $projectClassesNamespaces
                    ));

                self::_writeToFile('ns-' . $ns->name . '.html', $output, $html);
            }
        }
    }
    
    
    
    /**
     * Create index
     *
     * @param \Twig_Environment $twig Twig instance
     * @param JSObjects\Package[] $packages Array of packages to output
     * @param JSObjects\JSObject[] $projectClassesNamespaces Array of classes of project (so that we are able to create links)
     * @param string $output Root directory for output folder
     */
    static protected function _outputIndex(\Twig_Environment $twig, array $packages, array $projectClassesNamespaces, $output)
    {
        $template_index = $twig->load('index.html');
        $template_index_packages = $twig->load('index_packages.html');
        
        
        // create index navigation
        $html = $template_index->render(array(
                'packages' => $packages,
                'classmap' => $projectClassesNamespaces
            ));

        self::_writeToFile('index.html', $output, $html);
        

        // create index content (iframe content with list of packages)
        $html = $template_index_packages->render(array(
                'packages' => $packages
            ));

        self::_writeToFile('index_packages.html', $output, $html);
    }
    
    
    
    /**
     * Create the doc files : packages + index
     *
     * @param JSObjects\Package[] $packages Array of packages to output
     * @param JSObjects\JSObject[] $projectClassesNamespaces Array of classes of project (so that we are able to create links)
     * @param string $output Root directory for output folder
     * @param string $cache Cache folder
     */
    static public function output(array $packages, array $projectClassesNamespaces, $output, $cache)
    {
        $loader = new \Twig_Loader_Filesystem(rtrim(__DIR__,'/') . '/Templates/');
        $twig = new \Twig_Environment($loader, array(
            'cache' => false,//$cache,
            'strict_variables' => true,
            'auto_reload'=>true/*,
            'debug'=>true*/
        ));
        
        
        // sort packages, classes, properties, methods
        self::_sort($packages);

        
        // output doc files and index
        self::_outputFiles($twig, $packages, $projectClassesNamespaces, $output);
        self::_outputIndex($twig, $packages, $projectClassesNamespaces, $output);
    }
    
}



?>