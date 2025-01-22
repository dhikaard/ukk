import local from '@/utils/local-storage';

const helper = {
    methods: {
      hasAccess(...requiredAccess) {
        const permissions = local.get('permissions');
        
        return requiredAccess.every(access => permissions.includes(access));
      },
    },
  };
  
  export default helper;
  