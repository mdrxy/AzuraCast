<?php

declare(strict_types=1);

namespace App\Controller\Frontend\PublicPages;

use App\Entity\PodcastEpisode;
use App\Entity\Repository\PodcastEpisodeRepository;
use App\Entity\Repository\PodcastRepository;
use App\Exception\PodcastNotFoundException;
use App\Exception\StationNotFoundException;
use App\Http\Response;
use App\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;

final class PodcastEpisodeAction
{
    public function __construct(
        private readonly PodcastRepository $podcastRepository,
        private readonly PodcastEpisodeRepository $episodeRepository
    ) {
    }

    public function __invoke(
        ServerRequest $request,
        Response $response,
        string $station_id,
        string $podcast_id,
        string $episode_id
    ): ResponseInterface {
        $router = $request->getRouter();
        $station = $request->getStation();

        if (!$station->getEnablePublicPage()) {
            throw new StationNotFoundException();
        }

        $podcast = $this->podcastRepository->fetchPodcastForStation($station, $podcast_id);

        if ($podcast === null) {
            throw new PodcastNotFoundException();
        }

        $episode = $this->episodeRepository->fetchEpisodeForStation($station, $episode_id);

        $podcastEpisodesLink = $router->named(
            'public:podcast:episodes',
            [
                'station_id' => $station->getId(),
                'podcast_id' => $podcast_id,
            ]
        );

        if (!($episode instanceof PodcastEpisode) || !$episode->isPublished()) {
            $request->getFlash()->error(__('Episode not found.'));
            return $response->withRedirect($podcastEpisodesLink);
        }

        $feedLink = $router->named(
            'public:podcast:feed',
            [
                'station_id' => $station->getId(),
                'podcast_id' => $podcast->getId(),
            ]
        );

        return $request->getView()->renderToResponse(
            $response
                ->withHeader('X-Frame-Options', '*')
                ->withHeader('X-Robots-Tag', 'index, nofollow'),
            'frontend/public/podcast-episode',
            [
                'episode' => $episode,
                'feedLink' => $feedLink,
                'podcast' => $podcast,
                'podcastEpisodesLink' => $podcastEpisodesLink,
                'station' => $station,
            ]
        );
    }
}
