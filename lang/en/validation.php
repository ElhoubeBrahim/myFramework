<?php

	/**
	 * This file contains validation errors for each validation rule
	 * To translate those messages please copy this file to your language
	 * directory and translate messages
	 */

	return [
		'url' => 'Please enter a valid url',
		'email' => 'Please enter a valid email address',
		'required' => 'The {field} field is required',
		'unique' => 'the {field} must be unique',

		'min' => 'The {field} must be greater than {min}',
		'max' => 'The {field} must be less than {max}',
		'between' => 'The {field} must be between {min} and {max}',

		'length' => 'The {field} must contain {size} chars',
		'min_length' => 'The {field} must contain more than {min} chars',
		'max_length' => 'The {field} must contain less than {max} chars',

		'words' => 'The {field} must contain {words} word',
		'min_words' => 'The {field} must contain more than {min} word',
		'max_words' => 'The {field} must contain less than {max} word',

		'numeric' => 'The {field} must be a number',
		'alpha' => 'The {field} must contains alphabets only',
		'alpha_numeric' => 'The {field} must contains alphabets and numbers only',

		'same' => 'The {field} must match {same}',
		'different' => 'The {field} must not match {same}',

		'regex' => 'The {field} must match {pattern} pattern',
		'not_regex' => 'The {field} must not match {pattern} pattern',

		'file' => 'Please upload a file',
		'image' => 'Please upload an image',
		'extensions' => 'Please upload {extensions} file',
		'accept' => 'Please upload {mimes} file',

		'size' => 'Please upload a file with {size} bytes',
		'max_size' => 'Please upload a file less than {max} bytes',
		'min_size' => 'Please upload a file more than {min} bytes'
	];