<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\Message;
use App\Entity\Category;
use App\Entity\Subject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Create 5 users
        $users = [];
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setUsername($faker->userName())
                ->setEmail($faker->email())
                ->setPassword($faker->password());
            $manager->persist($user);
            $users[] = $user;
        }

        // Create 3 articles with comments
        for ($i = 0; $i < 3; $i++) {
            $article = new Article();
            $article->setTitle($faker->sentence())
                ->setContent($faker->paragraphs(3, true))
                ->setAuthor($faker->randomElement($users));

            // Add 2-5 comments to each article
            $commentCount = $faker->numberBetween(2, 5);
            for ($j = 0; $j < $commentCount; $j++) {
                $comment = new Message();
                $comment->setContent($faker->paragraph())
                    ->setAuthor($faker->randomElement($users))
                    ->setArticle($article);
                $manager->persist($comment);
            }

            $manager->persist($article);
        }

        // Create 2 categories with 5 subjects each
        for ($i = 0; $i < 2; $i++) {
            $category = new Category();
            $category->setTitle($faker->word());

            for ($j = 0; $j < 5; $j++) {
                $subject = new Subject();
                $subject->setTitle($faker->sentence(3))
                    ->setCategory($category);

                // Add 3-7 messages to each subject
                $messageCount = $faker->numberBetween(3, 7);
                for ($k = 0; $k < $messageCount; $k++) {
                    $message = new Message();
                    $message->setContent($faker->paragraph())
                        ->setAuthor($faker->randomElement($users))
                        ->setSubject($subject);
                    $manager->persist($message);
                }

                $manager->persist($subject);
            }

            $manager->persist($category);
        }

        $manager->flush();
    }
}