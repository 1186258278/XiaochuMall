<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb0b4bf247365ff876b95360090d83bef
{
    public static $files = array (
        'def43f6c87e4f8dfd0c9e1b1bab14fe8' => __DIR__ . '/..' . '/symfony/polyfill-iconv/bootstrap.php',
        '8825ede83f2f289127722d4e842cf7e8' => __DIR__ . '/..' . '/symfony/polyfill-intl-grapheme/bootstrap.php',
        'e69f7f6ee287b969198c3c9d6777bd38' => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/bootstrap.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
        '25072dd6e2470089de65ae7bf11d3109' => __DIR__ . '/..' . '/symfony/polyfill-php72/bootstrap.php',
        'b46ad4fe52f4d1899a2951c7e6ea56b0' => __DIR__ . '/..' . '/voku/portable-utf8/bootstrap.php',
        'a9ed0d27b5a698798a89181429f162c5' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/customFunctions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'v' => 
        array (
            'voku\\helper\\' => 12,
            'voku\\' => 5,
        ),
        'Z' => 
        array (
            'Zxing\\' => 6,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Php72\\' => 23,
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'Symfony\\Polyfill\\Intl\\Normalizer\\' => 33,
            'Symfony\\Polyfill\\Intl\\Grapheme\\' => 31,
            'Symfony\\Polyfill\\Iconv\\' => 23,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'voku\\helper\\' => 
        array (
            0 => __DIR__ . '/..' . '/voku/anti-xss/src/voku/helper',
        ),
        'voku\\' => 
        array (
            0 => __DIR__ . '/..' . '/voku/portable-ascii/src/voku',
            1 => __DIR__ . '/..' . '/voku/portable-utf8/src/voku',
        ),
        'Zxing\\' => 
        array (
            0 => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib',
        ),
        'Symfony\\Polyfill\\Php72\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-php72',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'Symfony\\Polyfill\\Intl\\Normalizer\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer',
        ),
        'Symfony\\Polyfill\\Intl\\Grapheme\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-intl-grapheme',
        ),
        'Symfony\\Polyfill\\Iconv\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-iconv',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Normalizer' => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/Resources/stubs/Normalizer.php',
        'Symfony\\Polyfill\\Iconv\\Iconv' => __DIR__ . '/..' . '/symfony/polyfill-iconv/Iconv.php',
        'Symfony\\Polyfill\\Intl\\Grapheme\\Grapheme' => __DIR__ . '/..' . '/symfony/polyfill-intl-grapheme/Grapheme.php',
        'Symfony\\Polyfill\\Intl\\Normalizer\\Normalizer' => __DIR__ . '/..' . '/symfony/polyfill-intl-normalizer/Normalizer.php',
        'Symfony\\Polyfill\\Mbstring\\Mbstring' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/Mbstring.php',
        'Symfony\\Polyfill\\Php72\\Php72' => __DIR__ . '/..' . '/symfony/polyfill-php72/Php72.php',
        'Zxing\\Binarizer' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Binarizer.php',
        'Zxing\\BinaryBitmap' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/BinaryBitmap.php',
        'Zxing\\ChecksumException' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/ChecksumException.php',
        'Zxing\\Common\\AbstractEnum' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/AbstractEnum.php',
        'Zxing\\Common\\BitArray' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/BitArray.php',
        'Zxing\\Common\\BitMatrix' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/BitMatrix.php',
        'Zxing\\Common\\BitSource' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/BitSource.php',
        'Zxing\\Common\\CharacterSetECI' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/CharacterSetECI.php',
        'Zxing\\Common\\DecoderResult' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/DecoderResult.php',
        'Zxing\\Common\\DefaultGridSampler' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/DefaultGridSampler.php',
        'Zxing\\Common\\DetectorResult' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/DetectorResult.php',
        'Zxing\\Common\\Detector\\MathUtils' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/Detector/MathUtils.php',
        'Zxing\\Common\\Detector\\MonochromeRectangleDetector' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/Detector/MonochromeRectangleDetector.php',
        'Zxing\\Common\\GlobalHistogramBinarizer' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/GlobalHistogramBinarizer.php',
        'Zxing\\Common\\GridSampler' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/GridSampler.php',
        'Zxing\\Common\\HybridBinarizer' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/HybridBinarizer.php',
        'Zxing\\Common\\PerspectiveTransform' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/PerspectiveTransform.php',
        'Zxing\\Common\\Reedsolomon\\GenericGF' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/Reedsolomon/GenericGF.php',
        'Zxing\\Common\\Reedsolomon\\GenericGFPoly' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/Reedsolomon/GenericGFPoly.php',
        'Zxing\\Common\\Reedsolomon\\ReedSolomonDecoder' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/Reedsolomon/ReedSolomonDecoder.php',
        'Zxing\\Common\\Reedsolomon\\ReedSolomonException' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Common/Reedsolomon/ReedSolomonException.php',
        'Zxing\\FormatException' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/FormatException.php',
        'Zxing\\GDLuminanceSource' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/GDLuminanceSource.php',
        'Zxing\\IMagickLuminanceSource' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/IMagickLuminanceSource.php',
        'Zxing\\LuminanceSource' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/LuminanceSource.php',
        'Zxing\\NotFoundException' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/NotFoundException.php',
        'Zxing\\PlanarYUVLuminanceSource' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/PlanarYUVLuminanceSource.php',
        'Zxing\\QrReader' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/QrReader.php',
        'Zxing\\Qrcode\\Decoder\\BitMatrixParser' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Decoder/BitMatrixParser.php',
        'Zxing\\Qrcode\\Decoder\\DataBlock' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Decoder/DataBlock.php',
        'Zxing\\Qrcode\\Decoder\\DataMask' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Decoder/DataMask.php',
        'Zxing\\Qrcode\\Decoder\\DecodedBitStreamParser' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Decoder/DecodedBitStreamParser.php',
        'Zxing\\Qrcode\\Decoder\\Decoder' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Decoder/Decoder.php',
        'Zxing\\Qrcode\\Decoder\\ErrorCorrectionLevel' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Decoder/ErrorCorrectionLevel.php',
        'Zxing\\Qrcode\\Decoder\\FormatInformation' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Decoder/FormatInformation.php',
        'Zxing\\Qrcode\\Decoder\\Mode' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Decoder/Mode.php',
        'Zxing\\Qrcode\\Decoder\\QRCodeDecoderMetaData' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Decoder/QRCodeDecoderMetaData.php',
        'Zxing\\Qrcode\\Decoder\\Version' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Decoder/Version.php',
        'Zxing\\Qrcode\\Detector\\AlignmentPattern' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Detector/AlignmentPattern.php',
        'Zxing\\Qrcode\\Detector\\AlignmentPatternFinder' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Detector/AlignmentPatternFinder.php',
        'Zxing\\Qrcode\\Detector\\Detector' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Detector/Detector.php',
        'Zxing\\Qrcode\\Detector\\FinderPattern' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Detector/FinderPattern.php',
        'Zxing\\Qrcode\\Detector\\FinderPatternFinder' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Detector/FinderPatternFinder.php',
        'Zxing\\Qrcode\\Detector\\FinderPatternInfo' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/Detector/FinderPatternInfo.php',
        'Zxing\\Qrcode\\QRCodeReader' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Qrcode/QRCodeReader.php',
        'Zxing\\RGBLuminanceSource' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/RGBLuminanceSource.php',
        'Zxing\\Reader' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Reader.php',
        'Zxing\\ReaderException' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/ReaderException.php',
        'Zxing\\Result' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/Result.php',
        'Zxing\\ResultPoint' => __DIR__ . '/..' . '/khanamiryan/qrcode-detector-decoder/lib/ResultPoint.php',
        'voku\\helper\\ASCII' => __DIR__ . '/..' . '/voku/portable-ascii/src/voku/helper/ASCII.php',
        'voku\\helper\\AntiXSS' => __DIR__ . '/..' . '/voku/anti-xss/src/voku/helper/AntiXSS.php',
        'voku\\helper\\Bootup' => __DIR__ . '/..' . '/voku/portable-utf8/src/voku/helper/Bootup.php',
        'voku\\helper\\UTF8' => __DIR__ . '/..' . '/voku/portable-utf8/src/voku/helper/UTF8.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb0b4bf247365ff876b95360090d83bef::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb0b4bf247365ff876b95360090d83bef::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb0b4bf247365ff876b95360090d83bef::$classMap;

        }, null, ClassLoader::class);
    }
}
