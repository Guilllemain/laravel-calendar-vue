module.exports = {
    theme: {
        extend: {}
    },
    variants: {},
    plugins: [
        function ({ addComponents }) {
            const buttons = {
                '.btn': {
                    color: '#fff',
                    width: '100%',
                    display: 'block',
                    textAlign: 'center',
                    textTransform: 'uppercase',
                    padding: '.75rem',
                    borderRadius: '.25rem',
                    letterSpacing: '0.05em',
                    backgroundColor: '#9f7aea',
                    lineHeight: '1.1',
                    transition: 'all .2s ease-in-out',
                    '&:hover': {
                        color: '#fff',
                        backgroundColor: '#b794f4'
                    },
                    '&:focus': {
                        outline: 'none'
                    },
                    '&:active': {
                        transform: 'scale(.95) translateY(4px)'
                    }
                }
            }
            addComponents(buttons)
        }
    ]
}
