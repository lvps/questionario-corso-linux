<?php


class Topic {
	public $lesson;
	public $speaker;
	public $title;

	public function __construct(string $lesson, string $speaker, string $topic) {
		$this->lesson = $lesson;
		$this->speaker = $speaker;
		$this->title = $topic;
	}

	public function summary(): string {
		return "Lezione $this->lesson: $this->title <small>($this->speaker)</small>";
	}
}
