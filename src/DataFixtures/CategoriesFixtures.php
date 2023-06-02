<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{
    private $counter;
    public function __construct(private SluggerInterface $slugger){}
    public function load(ObjectManager $manager): void
    {
        $parent = $this->createCategory(name:'Informatique',manager:$manager);
        $this->createCategory(name:'Ordinateur portable',parent:$parent,manager:$manager);
        $this->createCategory(name:'Ecran',parent:$parent,manager:$manager);
        $this->createCategory(name:'Souris',parent:$parent,manager:$manager);

        $parent = $this->createCategory(name:'Mode',manager:$manager);
        $this->createCategory(name:'Homme',parent:$parent,manager:$manager);
        $this->createCategory(name:'Femme',parent:$parent,manager:$manager);
        $this->createCategory(name:'Enfant',parent:$parent,manager:$manager);

        $manager->flush();
    }

    public function createCategory(string $name, Categories $parent = null, ObjectManager $manager){
        $categorie = new Categories();
        $categorie->setName($name);
        $categorie->setSlug($this->slugger->slug($categorie->getName())->lower());
        $categorie->setParent($parent);
        $manager->persist($categorie);
        $this->addReference('cat-'.$this->counter, $categorie);
        $this->counter++;

        return $categorie;
    }
}
