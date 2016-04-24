/*****************************************
Mathquill plugin for Kindeitor
@author Machinal.H <lvshuang1201@gmail.com>
*****************************************/

KindEditor.plugin('mathquill', function(K) {
	var editor = this,
		path = 'mathquill';

	editor.clickToolbar(name, function(){
		var jmeMath = [
			[
				"{/}frac{}{}","^{}/_{}","x^{}","x_{}","x^{}_{}","{/}bar{}","{/}sqrt{}","{/}nthroot{}{}",
				"{/}sum^{}_{n=}","{/}sum","{/}log_{}","{/}ln","{/}int_{}^{}","{/}oint_{}^{}"
			],
			[
				"{/}alpha","{/}beta","{/}gamma","{/}delta","{/}varepsilon","{/}varphi","{/}lambda","{/}mu",
				"{/}rho","{/}sigma","{/}omega","{/}Gamma","{/}Delta","{/}Theta","{/}Lambda","{/}Xi",
				"{/}Pi","{/}Sigma","{/}Upsilon","{/}Phi","{/}Psi","{/}Omega"
			],
			[
				"+","-","{/}pm","{/}times","{/}ast","{/}div","/","{/}bigtriangleup",
				"=","{/}ne","{/}approx",">","<","{/}ge","{/}le","{/}infty",
				"{/}cap","{/}cup","{/}because","{/}therefore","{/}subset","{/}supset","{/}subseteq","{/}supseteq",
				"{/}nsubseteq","{/}nsupseteq","{/}in","{/}ni","{/}notin","{/}mapsto","{/}leftarrow","{/}rightarrow",
				"{/}Leftarrow","{/}Rightarrow","{/}leftrightarrow","{/}Leftrightarrow"
			]
		];

		var menu = self.createMenu({
				name : name,
				beforeRemove : function() {
					removeEvent();
				}
			});
		var wrapperDiv = K('<div class="ke-plugin-emoticons">askdjfsaldfjakljflasjfklasjflkasjfklajlfjsaldjfaskl</div>');

		for (var i = 0; i < jmeMath[0].length(); i ++) {
			alert(jmeMath[0][i]);
		};

		menu.div.append(wrapperDiv);
	});

});