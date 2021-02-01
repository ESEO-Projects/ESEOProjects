<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\Form\FormFactoryInterface;
use App\Form\SearchProjectType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AppExtension extends AbstractExtension
{

    private $formFactory;
    private $url;

    public function __construct(FormFactoryInterface $formFactory, UrlGeneratorInterface $url)
    {
        $this->formFactory = $formFactory;
        $this->url = $url;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('users', [$this, 'formatUsers']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('searchForm', [$this, 'searchForm']),
        ];
    }

    public function formatUsers($users)
    {
        $chaine = "";
        foreach($users as $user){
            $chaine .= sprintf('%s %s | ', $user->getLastname(), $user->getFirstname());
        }
        return mb_substr($chaine, 0, -2); // On renvoit la chaîne créée, ôtée des deux derniers charactères ("| ")
    }

    public function searchForm()
    {
      $formFactory = $this->formFactory;
      $form = $formFactory->create(SearchProjectType::class, null, ['method' => 'GET', 'action' => $this->url->generate('project_search')]);

      return $form->createView();
    }
}
