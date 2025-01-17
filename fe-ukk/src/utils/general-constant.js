export const GeneralConstants = {
  ZERO: 0,
  YES: 'Y',
  NO:'N',
  OKE: 'OK',
  NULL_REF_VALUE_LONG: -99,
  EMPTY_VALUE: '',
  TYPE_COMPANY: 10,
  FAIL: 'FAIL',
  SUCCESS: 200,
  FORBIDDEN: 403,
  NULL: null,
  UNDEFINED: undefined,
  ROW_PERPAGE_OPTION: [5, 25, 50, 100],
  DEFAULT_ROWS: 25,
  DEFAULT_VALUE_ERR_MESSAGE: '#message',
  DOC_TYPES:[
      { name: 'All', docTypeId: '-99' },
      { name: 'Create Policy', docTypeId: '10001' },
      { name: 'Manage Policy', docTypeId: '10004' },
      { name: 'Void Policy', docTypeId: '10007' },

      { name: 'Create Procedure', docTypeId: '10002' },
      { name: 'Manage Procedure', docTypeId: '10005' },
      { name: 'Void Procedure', docTypeId: '10008' },

      { name: 'Create Form', docTypeId: '10003' },
      { name: 'Manage Form', docTypeId: '10006' },
      { name: 'Void Form', docTypeId: '10009' },
  ],
};