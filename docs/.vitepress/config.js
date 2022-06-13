export default {
    title: 'hue-it',
    description: 'Unoffical PHP SDK for the phillips hue api.',
    themeConfig: {
        footer: {
            message: 'Released under the MIT License.',
            copyright: 'Copyright &copy; 2022 Jordan Kniest'
        },
        nav: [
            { text: 'Home', link: '/' },
            { text: 'Getting started', link: '/#getting-started' }
        ],
        sidebar: [
            {
                text: 'Basics',
                items: [
                    { text: 'Getting started', link: '/' },
                ]
            },
            {
                text: 'Authentication',
                items: [
                    { text: 'Local authentication', link: '/authentication/local/' },
                    { text: 'Cloud authentication', link: '/authentication/cloud/' },
                ]
            },
            {
                text: 'Controlling devices',
                items: [
                    { text: 'Controlling lights', link: '/lights/' },
                    { text: 'Controlling groups', link: '/groups/' },
                    { text: 'Controlling bridge config', link: '/config/' },
                ]
            }
        ],
    }
}
