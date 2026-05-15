<?php
namespace App\Services\V2;

use App\Repositories\V2\RatingRepository;

class RatingService
{
    protected $ratingRepository;

    public function __construct(RatingRepository $ratingRepository)
    {
        $this->ratingRepository = $ratingRepository;
    }

    public function submitRating($session, $request)
    {
        return $this->ratingRepository->submitRating($session, $request);
    }
}
