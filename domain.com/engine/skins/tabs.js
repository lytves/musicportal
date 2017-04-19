	var textPadding = 16; // Padding at the left of tab text - bigger value gives you wider tabs
	var strictDocType = false; 
	var tabView_maxNumberOfTabs = 6;	// Maximum number of tabs
	
	/* Don't change anything below here */
	var dle_tabObj = new Array();
	var activeTabIndex = new Array();
	var MSIE = navigator.userAgent.indexOf('MSIE')>=0?true:false;
	var navigatorVersion = navigator.appVersion.replace(/.*?MSIE (\d\.\d).*/g,'$1')/1;
	var tabView_countTabs = new Array();
	var tabViewHeight = new Array();
	var tabDivCounter = 0;
	
	function setPadding(obj,padding){
		var span = obj.getElementsByTagName('SPAN')[0];
		span.style.paddingLeft = padding + 'px';	
		span.style.paddingRight = padding + 'px';	
	}
	function showTab(parentId,tabIndex)
	{
		var parentId_div = parentId + "_";
		if(!document.getElementById('tabView' + parentId_div + tabIndex)){
			return;
		}
		if(activeTabIndex[parentId]>=0){
			if(activeTabIndex[parentId]==tabIndex){
				return;
			}
	
			var obj = document.getElementById('tabTab'+parentId_div + activeTabIndex[parentId]);
			
			obj.className='tabInactive';
			var img = obj.getElementsByTagName('IMG')[0];
			img.src = 'engine/skins/images/tr_inactive.gif';
			document.getElementById('tabView' + parentId_div + activeTabIndex[parentId]).style.display='none';
		}
		
		var thisObj = document.getElementById('tabTab'+ parentId_div +tabIndex);	
			
		thisObj.className='tabActive';
		var img = thisObj.getElementsByTagName('IMG')[0];
		img.src = 'engine/skins/images/tr_active.gif';
		
		document.getElementById('tabView' + parentId_div + tabIndex).style.display='block';
		activeTabIndex[parentId] = tabIndex;
		

		var parentObj = thisObj.parentNode;
		var aTab = parentObj.getElementsByTagName('DIV')[0];
		countObjects = 0;
		var startPos = 2;
		var previousObjectActive = false;
		while(aTab){
			if(aTab.tagName=='DIV'){
				if(previousObjectActive){
					previousObjectActive = false;
					startPos-=2;
				}
				if(aTab==thisObj){
					startPos-=2;
					previousObjectActive=true;
					setPadding(aTab,textPadding+1);
				}else{
					setPadding(aTab,textPadding);
				}
				
				aTab.style.left = startPos + 'px';
				countObjects++;
				startPos+=2;
			}			
			aTab = aTab.nextSibling;
		}
		
		return;
	}
	
	function tabClick()
	{
		var idArray = this.id.split('_');		
		showTab(this.parentNode.parentNode.id,idArray[idArray.length-1].replace(/[^0-9]/gi,''));
		
	}
	
	function initTabs(mainContainerID,tabTitles,activeTab,width,additionalTab)
	{
		
		if(!additionalTab || additionalTab=='undefined'){			
			dle_tabObj[mainContainerID] = document.getElementById(mainContainerID);
			width = width + '';
			if(width.indexOf('%')<0)width= width + 'px';
			dle_tabObj[mainContainerID].style.width = width;
						
			
			var tabDiv = document.createElement('DIV');		
			var firstDiv = dle_tabObj[mainContainerID].getElementsByTagName('DIV')[0];	
			
			dle_tabObj[mainContainerID].insertBefore(tabDiv,firstDiv);	
			tabDiv.className = 'dle_tabPane';			
			tabView_countTabs[mainContainerID] = 0;

		}else{
			var tabDiv = dle_tabObj[mainContainerID].getElementsByTagName('DIV')[0];
			var firstDiv = dle_tabObj[mainContainerID].getElementsByTagName('DIV')[1];
			activeTab = tabView_countTabs[mainContainerID];		
	
			
		}
		
		
		
		for(var no=0;no<tabTitles.length;no++){
			var aTab = document.createElement('DIV');
			aTab.id = 'tabTab' + mainContainerID + "_" +  (no + tabView_countTabs[mainContainerID]);
			aTab.onclick = tabClick;
			aTab.className='tabInactive';
			tabDiv.appendChild(aTab);
			var span = document.createElement('SPAN');
			span.innerHTML = tabTitles[no];
			aTab.appendChild(span);
			
			var img = document.createElement('IMG');
			img.valign = 'bottom';
			img.src = 'engine/skins/images/tr_inactive.gif';
			// IE5.X FIX
			if((navigatorVersion && navigatorVersion<6) || (MSIE && !strictDocType)){
				img.style.styleFloat = 'none';
				img.style.position = 'relative';	
				img.style.top = '4px'
				span.style.paddingTop = '4px';
				aTab.style.cursor = 'hand';
			}	// End IE5.x FIX
			aTab.appendChild(img);
		}

		var tabs = dle_tabObj[mainContainerID].getElementsByTagName('DIV');
		var divCounter = 0;
		for(var no=0;no<tabs.length;no++){
			if(tabs[no].className=='dle_aTab'){
				tabs[no].style.display='none';
				tabs[no].id = 'tabView' + mainContainerID + "_" + divCounter;
				divCounter++;
			}			
		}	
		tabView_countTabs[mainContainerID] = tabView_countTabs[mainContainerID] + tabTitles.length;	
		showTab(mainContainerID,activeTab);

		return activeTab;
	}	