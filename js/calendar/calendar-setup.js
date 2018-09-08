/*  Copyright Mihai Bazon, 2002, 2003  |  http://dynarch.com/mishoo/
 * ---------------------------------------------------------------------------
 *
 * The DHTML Calendar
 *
 * Detalles y última versión en:
 * http://dynarch.com/mishoo/calendar.epl
 *
 * This script is distributed under the GNU Lesser General Public License.
 * Read the entire license text here: http://www.gnu.org/licenses/lgpl.html
 *
 * Este archivo define las funciones de ayuda para la creación del calendario. esta wa
 * la intención de ayudar a los no programadores tienen un calendario de trabajo en su sitio
 * rápidamente. Este script no debe ser visto como parte del calendario. sólo
 * muestra lo que uno puede hacer con el calendario, mientras que en el mismo tiempo
 * ofrece un método rápido y sencillo para su puesta en marcha. Si usted necesita
 * personalización exhaustiva del proceso de creación del calendario no dude en
 * modificar este código para satisfacer sus necesidades (esto es recomendable y mucho mejor
 * que la modificación de calendar.js sí mismo).
 */

// $Id: calendar-setup.js,v 1.25 2005/03/07 09:51:33 mishoo Exp $

/**
 *  This function "patches" an input field (or other element) to use a calendar
 *  widget for date selection.
 *
 *  The "params" is a single object that can have the following properties:
 *
 *    prop. name   | description
 *  -------------------------------------------------------------------------------------------------
 *   inputField    | el ID de un campo de entrada para almacenar la fecha
 *   displayArea   | el ID de un DIV u otro elemento para mostrar la fecha
 *   button        | Identificación de un botón u otro elemento que desencadenará el calendario
 *   eventName     | evento que activará el calendario, sin el prefijo "on" (por defecto: "clic")
 *   ifFormat      | formato de fecha que será almacenado en el campo de entrada
 *   daFormat      | el formato de fecha que se utilizará para mostrar la fecha en areaPantalla
 *   singleClick   | (verdadero / falso) bien sea desde el calendario está en modo de solo clic o no (por defecto: true)
 *   firstDay      | numérica: de 0 a 6. "0" significa que el primer domingo de pantalla, "1" significa primer lunes de pantalla
 *   align         | la alineación y si usted no sabe lo que es esto consulte la documentación del calendario
 *   range         | array with 2 elements.  Default: [1900, 2999] -- the range of years disponibles
 *   weekNumbers   | (verdadero / falso) si es verdad (por defecto), el calendario mostrará números de la semana
 *   flat          | ID nulo o elemento, si no nulo el calendario será un calendario plana que tiene el padre con el identificador dado
 *   flatCallback  | function that receives a JS Date object and returns an URL to point the browser to (for flat calendar)
 *   disableFunc   | función que recibe un objeto Date JS y debe devolver true si esa fecha tiene que ser desactivado en el calendario
 *   onSelect      | función que se llama cuando se selecciona una fecha. No _tiene_ para suministrar este (el valor predeterminado es en general bien)
 *   onClose       | función que se llama cuando el calendario está cerrado. [default]
 *   onUpdate      | función que es llamada después de la fecha se actualiza en el campo de entrada. Recibe una referencia al calendario.
 *   date          | la fecha en que el calendario se muestra inicialmente al.
 *   showsTime     | por defecto: false, si es cierto el calendario incluirá un selector de tiempo
 *   timeFormat    | el formato de hora, puede ser "12" o "24", por defecto es "12"
 *   electric      | si es cierto (por defecto) y luego da los campos / áreas fecha se actualizan para cada movimiento, de lo contrario están actualizados sólo en una estrecha
 *   step          | configura el paso de los años en cuadros desplegables, por defecto: 2
 *   position      | configura la posición absoluta del calendario, por defecto: null
 *   cache         | si true (pero por defecto: "false") se volverá a utilizar el objeto mismo calendario, siempre que sea posible
 *   showOthers    | if "true" (but default: "false") it will show days from other months too
 *
 *  Ninguno de ellos se requiere, todos tienen valores predeterminados. Sin embargo, si
 *  pasar ninguno de "campo de entrada", "areaPantalla" o "botón" obtendrá una advertencia
 *  diciendo "nada que configurar".
 */
Calendar.setup = function (params) {
	function param_default(pname, def) { if (typeof params[pname] == "undefined") { params[pname] = def; } };
	param_default("inputField",     null);
	param_default("displayArea",    null);
	param_default("button",         null);
	param_default("eventName",      "click");
	param_default("ifFormat",       "%Y/%m/%d");
	param_default("daFormat",       "%Y/%m/%d");
	param_default("singleClick",    true);
	param_default("disableFunc",    null);
	param_default("dateStatusFunc", params["disableFunc"]);	// tiene prioridad si ambos se definen
	param_default("dateText",       null);
	param_default("firstDay",       null);
	param_default("align",          "Br");
	param_default("range",          [1900, 2999]);
	param_default("weekNumbers",    true);
	param_default("flat",           null);
	param_default("flatCallback",   null);
	param_default("onSelect",       null);
	param_default("onClose",        null);
	param_default("onUpdate",       null);
	param_default("date",           null);
	param_default("showsTime",      false);
	param_default("timeFormat",     "24");
	param_default("electric",       true);
	param_default("step",           1);
	param_default("position",       null);
	param_default("cache",          false);
	param_default("showOthers",     false);
	param_default("multiple",       null);

	var tmp = ["inputField", "displayArea", "button"];
	for (var i in tmp) {
		if (typeof params[tmp[i]] == "string") {
			params[tmp[i]] = document.getElementById(params[tmp[i]]);
		}
	}
	if (!(params.flat || params.multiple || params.inputField || params.displayArea || params.button)) {
		alert("Calendar.setup:\n  Nothing to setup (no fields found).  Please check your code");
		return false;
	}

	function onSelect(cal) {
		var p = cal.params;
		var update = (cal.dateClicked || p.electric);
		if (update && p.inputField) {
			p.inputField.value = cal.date.print(p.ifFormat);
			if (typeof p.inputField.onchange == "function")
				p.inputField.onchange();
		}
		if (update && p.displayArea)
			p.displayArea.innerHTML = cal.date.print(p.daFormat);
		if (update && typeof p.onUpdate == "function")
			p.onUpdate(cal);
		if (update && p.flat) {
			if (typeof p.flatCallback == "function")
				p.flatCallback(cal);
		}
		if (update && p.singleClick && cal.dateClicked)
			cal.callCloseHandler();
	};

	if (params.flat != null) {
		if (typeof params.flat == "string")
			params.flat = document.getElementById(params.flat);
		if (!params.flat) {
			alert("Calendar.setup:\n  Flat specified but can't find parent.");
			return false;
		}
		var cal = new Calendar(params.firstDay, params.date, params.onSelect || onSelect);
		cal.showsOtherMonths = params.showOthers;
		cal.showsTime = params.showsTime;
		cal.time24 = (params.timeFormat == "24");
		cal.params = params;
		cal.weekNumbers = params.weekNumbers;
		cal.setRange(params.range[0], params.range[1]);
		cal.setDateStatusHandler(params.dateStatusFunc);
		cal.getDateText = params.dateText;
		if (params.ifFormat) {
			cal.setDateFormat(params.ifFormat);
		}
		if (params.inputField && typeof params.inputField.value == "string") {
			cal.parseDate(params.inputField.value);
		}
		cal.create(params.flat);
		cal.show();
		return false;
	}

	var triggerEl = params.button || params.displayArea || params.inputField;
	triggerEl["on" + params.eventName] = function() {
		var dateEl = params.inputField || params.displayArea;
		var dateFmt = params.inputField ? params.ifFormat : params.daFormat;
		var mustCreate = false;
		var cal = window.calendar;
		if (dateEl)
			params.date = Date.parseDate(dateEl.value || dateEl.innerHTML, dateFmt);
		if (!(cal && params.cache)) {
			window.calendar = cal = new Calendar(params.firstDay,
							     params.date,
							     params.onSelect || onSelect,
							     params.onClose || function(cal) { cal.hide(); });
			cal.showsTime = params.showsTime;
			cal.time24 = (params.timeFormat == "24");
			cal.weekNumbers = params.weekNumbers;
			mustCreate = true;
		} else {
			if (params.date)
				cal.setDate(params.date);
			cal.hide();
		}
		if (params.multiple) {
			cal.multiple = {};
			for (var i = params.multiple.length; --i >= 0;) {
				var d = params.multiple[i];
				var ds = d.print("%Y%m%d");
				cal.multiple[ds] = d;
			}
		}
		cal.showsOtherMonths = params.showOthers;
		cal.yearStep = params.step;
		cal.setRange(params.range[0], params.range[1]);
		cal.params = params;
		cal.setDateStatusHandler(params.dateStatusFunc);
		cal.getDateText = params.dateText;
		cal.setDateFormat(dateFmt);
		if (mustCreate)
			cal.create();
		cal.refresh();
		if (!params.position)
			cal.showAtElement(params.button || params.displayArea || params.inputField, params.align);
		else
			cal.showAt(params.position[0], params.position[1]);
		return false;
	};

	return cal;
};
