<?php

	Class extension_noediting extends Extension{

		public function about(){
			return array('name' => ' Filter: No Editing of Entries',
						 'version' => '1.0',
						 'release-date' => '2009-06-12',
						 'author' => array('name' => 'Nils Werner',
										   'website' => 'http://www.phoque.de/projekte/symphony',
										   'email' => 'nils.werner@gmail.com'),
						 'description' => 'Allows you to prevent users from editing entries from front end forms.'
				 		);
		}
		
		public function getSubscribedDelegates(){
			return array(
						array(
							'page' => '/blueprints/events/new/',
							'delegate' => 'AppendEventFilter',
							'callback' => 'addFilterToEventEditor'
						),
						
						array(
							'page' => '/blueprints/events/edit/',
							'delegate' => 'AppendEventFilter',
							'callback' => 'addFilterToEventEditor'
						),
						
						array(
							'page' => '/blueprints/events/new/',
							'delegate' => 'AppendEventFilterDocumentation',
							'callback' => 'addFilterDocumentationToEvent'
						),
											
						array(
							'page' => '/blueprints/events/edit/',
							'delegate' => 'AppendEventFilterDocumentation',
							'callback' => 'addFilterDocumentationToEvent'
						),
						
						array(
							'page' => '/frontend/',
							'delegate' => 'EventPreSaveFilter',
							'callback' => 'processEventData'
						),						
			);
		}
		
		public function addFilterToEventEditor($context){
			$context['options'][] = array('no-editing', @in_array('no-editing', $context['selected']) ,'No Editing of Entries');		
		}
		
		public function addFilterDocumentationToEvent($context){
			if(!in_array('no-editing', $context['selected'])) return;
			
			$context['documentation'][] = new XMLElement('h3', 'Prevent Editing');
			
			$context['documentation'][] = new XMLElement('p', 'Prevents users from supplying an entry ID to edit entries like so:');
			$code = '<input name="id" type="hidden" value="23" />';

			$context['documentation'][] = contentBlueprintsEvents::processDocumentationCode($code);
			
			$context['documentation'][] = new XMLElement('p', 'The following is an example of the XML returned form this filter:');
			$code = '<filter name="no-editing" status="passed" />
					<filter name="no-editing" status="failed">You are not allowed to edit entries.</filter>';

			$context['documentation'][] = contentBlueprintsEvents::processDocumentationCode($code);
			
		}
		
		public function processEventData($context){
			
			if(!in_array('no-editing', $context['event']->eParamFILTERS)) return;
			
			if(isset($_POST['id'])) {
				$context['messages'][] = array('no-editing', false, 'You are not allowed to edit entries.');
			}
			
		}
		
		public function uninstall(){
			return true;
		}
		
	}

