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
                    transition: 'color .2s ease-in-out, background-color .2s ease-in-out, transform .2s ease-in-out',
                    '&:hover': {
                        color: '#fff',
                        backgroundColor: '#b794f4'
                    },
                    '&:focus': {
                        outline: 'none'
                    },
                    '&:active': {
                        transform: 'scale(.95) translateY(4px)'
                    },
                    '&:disabled': {
                        opacity: .4,
                        cursor: 'not-allowed'
                    },
                    '&:disabled:hover': {
                        backgroundColor: '#9f7aea'
                    }
                }
            }
            addComponents(buttons)
        }
    ]
}
