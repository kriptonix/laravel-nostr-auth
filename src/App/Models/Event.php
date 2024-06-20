<?php

declare(strict_types=1);

namespace Kriptonix\LaravelNostrAuth\App\Models;

use Illuminate\Database\Eloquent\Model;
use Mdanter\Ecc\Crypto\Signature\SchnorrSignature;

class Event extends Model
{
    /**
     * The event kind.
     *
     * Override this property in your custom events to set the value
     * immediately.
     *
     * @var int
     */
    protected int $kind = 0;

    /**
     * The event id.
     *
     * @var string
     */
    protected string $id = '';

    /**
     * The event signature.
     *
     * @var string
     */
    protected string $sig = '';

    /**
     * The public key.
     *
     * @var string
     */
    protected string $pubkey;

    /**
     * The event content.
     *
     * @var string
     */
    protected string $content = '';

    /**
     * The created at timestamp.
     *
     * @var int
     */
    protected int $created_at = 0;

    /**
     * The event tags.
     *
     * @var array
     */
    protected array $tags = [];

    /**
     * {@inheritdoc}
     */
    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublicKey(string $public_key): static
    {
        $this->pubkey = $public_key;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSignature(string $sig): static
    {
        $this->sig = $sig;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setKind(int $kind): static
    {
        $this->kind = $kind;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setTags(array $tags): static
    {
        foreach($tags as $tag) {
            $this->tags[] = $tag;
        }
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt($time): static
    {
        $this->created_at = $time;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function verify(): bool
    {
        try {
            $computedId = hash(
                'sha256',
                json_encode(
                    [
                        0,
                        $this->pubkey,
                        $this->created_at,
                        $this->kind,
                        [],
                        $this->content,
                    ],
                    \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE,
                ),
            );
        } catch (\JsonException) {
            return false;
        }

        if (!hash_equals($computedId, $this->id)) {
            return false;
        }

        return (new SchnorrSignature())->verify($this->pubkey, $this->sig, $this->id);
    }

}
