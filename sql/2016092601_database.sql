--
-- Change name of some columns
--

ALTER TABLE `coupons` CHANGE `start_date` `startDate` DATE NULL DEFAULT NULL;

ALTER TABLE `coupons` CHANGE `expire_date` `expireDate` DATE NULL DEFAULT NULL;

--
-- Add "No logo" image for coupons
--

INSERT INTO `images` (`id`, `width`, `height`, `mime`, `size`, `type`, `name`, `content`) VALUES
(1, 270, 270, 'image/jpeg', 7377, 'coupon_no_image', 'noimage.jpg', 0xffd8ffe000104a46494600010101004800480000ffdb004300040303030403040404040604040406080604040608090707080707090b0909090909090b090b0b0c0b0b090e0e0f0f0e0e141414141416161616161616161616ffdb004301050505090809110b0b1114100d101418171717171818161616161616161616161616161616161616161616161616161616161616161616161616161616161616ffc0001108010e010e03011100021101031101ffc4001c0000020301010101000000000000000000000105060704020308ffc400511000010302020309080d0a060203000000010002030411052106123113415161718191b1c1071422323572a1d123253342525362738292b2e1f0151634546383a2b3c2f11726364374932455d2e2f2ffc400160101010100000000000000000000000000000102ffc4001511010100000000000000000000000000000001ffda000c03010002110311003f00fdb4a810080402010080402010080402010080402010080402010240ae80408a0480409022502ba044a057409075201008040201008040201008040201008040201008040204812048120102281200941e502409008120eb4020100804020100804020100804020100804020100812048120480408a048120481204804090241d880402010080402010080415677743d166b8b4d53eed363ec526f732a8f3fe22e8afeb4ff00faa4f520b4b1ec7b1af61d663c02c770822e0f41515e9040e29a618161956696b277473068716863dd91e40a8e2ff11b44ff005b7ffd527a9113984e31418a52f7cd1c9ba43ac597208cc72f2a2bbd4070f120ae62fa6da3f8748e8a4a833ccddb0c035cf60f4a22bafeeb143ade061d339bbf77b41e8cfad51df41dd3347aa1c1b309a8c936d69002ce9693d482dd0d4433c4d96191b2c6ef15ec21c3d0a2be88120481140902442402045024524476a284020100804020100815ca0fcdf50077c4be7bbad6997cb5515b4f73cc5bbf700642f75e6a03b93afb753de1ec505b7d765158cf74acf49dff00331f52a8a86a8546c5dcc7fd36eff932753541735159669c69bcd34b2e1b86ca63819e054543327388dad69e0eb5519ed9506ae5c080b7dc826747b492bf05aa6c903b5e03eed4c7c470e4de3c0836cc3712a5c428a2aba67ebc528cafb411b5a5457512a057408940ae8122040902250228120ee45080402010080402010083f395437d9e4f38f5ad32f9ea8455abb9fe2bde38f3227bad0d68dc9fbde1fbc3d3973a83671bca2b1bee8e3fccaff998fa95454aca8d7fb9a65a38fe2a993a9aa0ebd39c69d8760af113b56a2acee511df02de111c97418aeaaa3e94f4d34f3c70c2c324b29d58d837c941a1d2772c1deed3555e59505b7708da1cd6df8c9f0bd0a0a8690e8dd660d53b9cc77585f7dc6a46c701d442a21f5505dbb9d632ea7c45f87c8ff60adb98dbc128cf2e50a0d4ef751490240204882e811283c9409005077a28402010080402010080df41f9daa07b3c9e71eb5a65f3b229b4b98e6bdb939a6ed3c7bc88deb00c4db896154b597bbe56fb371483277a7359699877451fe647fccc7d4aa2a76546b7dcdffd3ceff93275354156ee915c66c6e3a60ebb6921197cb93c227a2c829b6d9e9545ebb99e1ad92baa2bdedbf7ab4323bef3e4bdcf2803d2a0d354540699e1adaed1faa1ab7969c6ed11dfbb33b7384462f65a1f6a59e4a6a986a19e3c2f0f672b73506f714cc96264ad376c8359bc8ecd41e9142048122120450240140904822840201008040201008041f9eaa07b3cbe71eb5a65e036e401b4a296a9dfcb788f4141a0f733c53565a9c3a4764ff6680718c9fe8b28223ba18ff31bfe663ea4154b2a35aee73968fbb87be5f6e86a9467ba532997487127937f67737febf03fa50445951aaf73c80330073ed9cb50f774003b1416e515e25635f1b98e170f05a790e483019232c91cc3b58757a169979b22b6ad1994cb8061cee085adfabe0f62c8954090245244240902402045048a2840201008040201008041f9fa71ecf2f9e56997cc5c1d606c5b98e5ba2a7b4bf0d1478ccae637560ab02a22e47ede8282330bae930fc4a9aad9e353b83cb785bbe39c209bd3c7b25c777561d68e4823746ee1045d0562db79106addcf3c82eff90fea6a9466d8b8be2d5c786a243fc6551c5641aee838b68cd21f866427fec70ec5058aea2912830cc498062356ddbab3482fc8e21565cb6555aee8593f9b3437fda0e891ca09d5022502283c9280ba04812048041248a100804020100804020106033b6d3cbe7bbad6997ced750697a658677ce8e52d5b5b792858d738fecdc0077628acd00cff001bd92d0e9a9a97cf15287e6ea78f721e6871737ed590735906a5dcfbc82ef9f7f5354a33bc6632cc63106f054ca3f8ca0e1b7e39951ab6833c3b47a16837d49241d2ebf6a82c6a28bf420c32adc1f5333c661d238f4b8aacbe36c9556b9a22dd4d1da26f13cf4bdc7b541344a83c940900502409024020f2504a22840201008040201008120c1aa07b3cbe7bbad6997caca0dc608629b0b862906b472c218f6f08736c428ac6310a2928abaa2964db0bcb7946f15472d82a1d9069fa01e42771543fa828297a5f4c61d20abcac24d591bf487ad041db838151a3773ca80ec3eaa9ef9c536b5b89e2dfd2a0b81515c988d48a6a0a9a826c228dcef420c54ed5592b22b66c2a034f8651c2458b216070e3b66a0eb281200a048120481204502412a8a10080402010080402045061338f6797cf775ad32f981d6a0dc287f42a6f9a6fd951545ee83865a7a7af60ca51b94de737c53ce2ea8a4d95090699a07e433f3efea0a08cee83406f4d5cd195b7294f3ddbda828f6544d68be2c30ec4c3e4368261b9cfe69dfe6506aad7b5ed0e638398ecdae1be389414ed38c6636d38c3a27874b2d9d501a6f668370d3caa8a0db8f66d5448607879adc569a0b5da5c1f2798dcca835c50081201022812048120481209645080402010080402045024186cedf6797cf775aacbc6aa0dae87f42a7f9a67528d3971cc3457e17514db5cf6fb179e336fa55463c5a6f9e477c2a116a0d27417c88ef9f7f53541378950c55d43352cbe2cadb03c0778f4a0c8ab28a7a4a9929a76eacb11b1e3e31caa8f86a8f5841d70e2388c3198e1ab9a261f7ad790141ca6e4925d727324e6550ac3241a1687e0cfa3a6755ccdd59ea4598d3b5acdbe95059eea048120480409022502ba0481209745080402010081205740201063b360d8c6ed29fc9f5162e363b93f87915478fc8d8bff00ebea07ee9fea41add202da585a458b58d041e20a2bec5066ba4b80d6371799f4d4b24d14fecb78985c0176d1971aa88838362dfa8540fdd3fd482fba1f4d514f84164f0be17eecf3aaf6969b65c282c0a087c7300a4c4e2f0fd8e768b453f61e15450710d1dc5689c75e9dd246364cc05edf45ec8228edb6fa0e9a6a0aca9786434ef949f8232e936082e381e87b20919515faaf9599b29c66d078cefa0b5a804090240204502408a048120482611420100804020f3740902b840205e941e6fc4882e803f7a0f37406b20477cf392815d0227fba0f27a1079746c71b9683cc107a1b32c8706f20f2f3619ec1c283e5df34fb37565fce083e971cdc2815f8ac8041e4a04802812048120482651420102fc74a0ca31daaab6e335cd6cf234099f601c6db55474e89d4d53f1fa46be791cd3ba5da5c48f73720d32ea2aa7a7751247454ad63dcc2f973d536d83ef55145efcacfd624fac5544b68cd7d4b71ba4d79de5af7166a97123c2040eb515a6b8d8127202f75063d257563dee7eecf1ac6f60e3bea8f3df959fac49f58a0d2345e67c981d339eed77f841ce3b7271504ba01155ed31ab743841631da8f9a40d0e191b0ccaa8a0f7dd65ff4893eb14459f42ab24357510cb239faed6b9bac6fe29cfed20ba28a4822f1bc6a1c3a9ee407cb27b9467acaa33faec4ebab1e5d3cc48de66c68e641c5aa2d6c9512187e315f42f06294ea6fc4e3769e95068185e27057d2b668f23b248f7da77d41e31c71184d5906c430d88419c77cd57c7c9f58aa0ef9aaf8f93eb14077c557c73feb1f5a0d4940204811282691420102419263de5aaff9e7f5aa8e9d12ff0050527ef3f96e41a7a8aa4e9fbee6819f38e3fc215452d51d145288aae09766e5231ff55c0a88d4f169b72c2eb24df10bc83c7abeb515922a03b151a0e85c9ad84bdbf02670e90d3daa0b1a83cb905234e2a35aa29a0de630b88f38dbfa55154544a68f54ee18c52bf6073f50f2385941a5a8117581272036f26fa0cc317af7d657cd31cdbad68870346c547c28e925aba8641136ef79dbc1c25516a768545b859b52fddb78dbc1e850546489d14cf8e4167c64b5e39104de8a4d511e23aad6b9d14cdd595d6c81da09505ab1d3ed4d5f1c7ea419b8b2a25b0ac0a4c4217cac95b1863b573e4baa3bff0033ea3f596741505bc95017408941e504e22920574020c9b1d1edcd77cf3bad5474e898f6fe93f79fcb720d341cff001c2a2a83a72ebe214ecf830dfa49f52a8acc7197bc306d3b151e059446838cd517689896fe14f1c57e5241776a8aa03185ee6b40cce4150e48f525730fbc241e6545cb41a4f60ab670398e3ce08fe9505b2ea044a0ccf4867ddf18aa75ee18ed41f432547332903a826a8f8b998c1ce1e4f50547330eab8386d69041506ab4d2eed4f14bf18c0ee95071e3b398709aa78363a9aadfa5976a0cd32b2a2d5a1b4c35eaaa37da046c3cb7d6ea082dfdaa08a9700c3a5ad92aa56991f210751c7c1c85b60b209163238d8191b431a3635a00083831cf24d57988339d80aa2e5a202d433706ebd810584f2a812048120104da2920480ba0ca71cf2c577cf3bad565d3a2be5ea4fde7f2dc8ad2b7d419de983f5b1a70f831b076f6aa387028c498bd2348bddf98e62a8e09232c91ccdf6920f35c282c15b55afa25431dfc21316bbe8eb7ff002082270a8b74c528d9b4199971ce0a0f188b35710aa6dbc599e3f88aa27f425dff0095551fc28c1faa6dfd4a0ba5d41f39e511c324a7644d2e3cd9a0ca1c4b9ce79daf25ce3c655163a4a42ed13ac76f994c80f132dea282b761cd9a0bfe8c546e9844436984961eb1e8283e7a58eb6136f852b41f49ec4143b65f8e0545db445b6c3643c333ba9aa0e9c5b1c6e1f246c309977417b836ec2823bf3c63fd51df5bee40bf3c23fd51df5bff00aa096c61dad84549e18eea0cf2ca8eba5c46b695ae6c1298dae3723239f3aa24f0cc671396be9e39272e63dfe10b0f5282e24a812010241368a48120106578df95ebbe79dd6ab2e9d161eded27ef3f96e45691bea0cd3491daf8d551e02d1d0d0150f469a4e37496e171e86141f1c76110e2f56cb65ba170e47f85da8395d397524707bd63defe77868fe95449e8bc3ba63501f8a0e79e616ed5072e36c0dc5ab07ed5c4f3a0efd127eae2d6f871b8751ec417b5044692ce62c1e7b1ce5b463e91cfd17419f6ff00120bfe134bfe5f8a13feec2ebe5f197f5a0a016d8969deb83d4a8b4687ce03ea60e101e072647b1048695b2f851f932b4f676a0a358595174d1237c31e3f6c7d2d695047697fe914de61415cc9505b3505fb15f23d40fd928281c2a8eea4c2ab2a985f0b039ad3626e075aa24b0ec0f1186b6095f180c8dd73983d4a0b6280409024138515e5008114465d8d8f6deb7e79dd6a8e8d171eded27d3fe5b9068ca0cbf1776be2958efdb3edcceb2a3af45997c6e9b3d9aff60a0e9d31a7d4c463940f76887d669cfac20aed82a2d3a170ff00e4554bf0181bf58fdca08bd2365b1aaa1c6d3d2d080d1d76ae334bc64b7a5a420d094155d329fc1a5838cc879b21daa8a9d904ec5a535f1c51c6d8a1d58da1adbb5dbc2df090424aedd257c84069792481b332a891d1f9f71c560e092ec77d2197a6ca0b7e33019b0da967bed4d61cadcd419ee5b7915166d11980ef8a7be793da39323d882ce5b19b6b006dbea0a7e94b9bdf9146db78118d6b7095443d34264a98631efde07494179c67c9955e6282816c8aa2d9a2dfa24ff3bd8104e950080408940ae826d14204811288cc31af2b56fcf3bad51d1a31e5ca5fa7f61c8344ec5065352fd7a895ff000dce3d25544be898be30ce263ba9152fa650eb5253cbf17211cce17ec414cd5f5a0bb688c5a9874925bdd6539f1342820f4a5b6c624f94d69f45bb1511f8649b9e2348ff00832b2fd2834ad87a94142d259b74c5641bd100c1d7daa88ea4a596a6a19045e3bf65f6642ea896fcd4c53862fadf72823b10c3aa28656c735b59cdd6f0735472b1e59235e36b0823941c941a635ed9230edad91a0f3387a9419fe2942ea4ac92323c1275a23f24aa3e349532d2d43278fc761d9bc55164fceb837307bddfba0f7996af4fdca0acd4d44b513be693c779d8a893d1ca432d6eec478100d6e73905059318f265579854142de544ae198cba8a27c621126bbb5ae4dbb1076fe7549fab0fadf720b235d7683c2a064a0f24a05741385149004a0f3e8446698c4721c56b086120caeb1b1547468dc6f6e354ce2d2d035f320fc0282fd33b52291dc00a832c31497f11dd1c6aa27744e378c4dc4b48021767ce11564d208b76c22a0346b168d61f45ca0cfb72977e377420d0b058772c2691a458960711e71415ad2c89ff009498434b8185b9f39544231b235ed76a3b2703b38106965ed009e75066f52649aa259751de1b89e92a897d16a7777f49296db728f2bf0b8dba905c0fe3f1cca0ade96d397474d2817d425846d3e1663a95157dce43ef0a0bce05297e1907c28eedb1e2397a140f15c322ad8354d9b237dcde78f79514baba0aba67ea4d116f01cc83d083979907651e1d5755206c519e37ec684173a1a2868e9c44ccf7deee1720f9e2f7fc9b5395c966c5051f737fc12a83737fc1280dce4de61e841a047e2379140dc507940209c45240902288387f1d4815ff001976a059fbd408a0f28027340bfba039102b204e41e5c4f0fa7ef40657ec4089080df28173a04815ff00b2047676204eb1b8dee3fc141f0ef4a5bdf7065f875420fa8000b0197a102dee240901ce811408f4a0f37409008041368a4812212088d21c4a5a2a26ba176acaf786b49e437415b874a315dda3dd256966b0d71a8d1972d905af179aa23c3a69a9dd67b06b0c81c86df46682a90e93e27bb47ba48d31eb0d7f046cdf4177dfe2282b38fe395b4b58d829de1a1b182fc81ccf2a0e9d1fafafad13baa1e1cc610d659a067bfb1047e338ed6c15f2434f206b23006c073b2a3a347f18abaaa89229dc1c757599901b36ece5416150525fa478b091c04ad02f9782104ee275d530e111d4b1decae0cb9b0f7c10466118de215188c314af0e63b5ae2c06c69e0416840bb5056317c6eb61ae7c503c358c006c07ad51f7c0b16a9a99e48a77071d5d68ec00d9b7672a09dfc050539fa418987b8090581c86a840d9a4789075dc439bbe0b40ea4161c3b1086b60d768d478f748f80a086c5717ae82be5863900636d616076b41df412b83554d51442494eb38b88bd80ea41eb16a9960a27cb11b3c116360769b6fa0adfe5ec4cfbf1f547a907da9b19c45f510c6e906ac8f6b4e406d4168f42048040904d128a2e8122114150d319b5aa29e1bf88d2eb79dff00e55159731c368f185c20d130c945561501766248b51fd1aa5419f4f018a69223e346e2d3d28343c2aa377c3a9a426e5d180e3c6323d2828989cfdf15f3cb7b873ceaf20d9e84171c029f70c2a1be46406471e5d9e8414aa97ba7ab964033964247212a8e9c126dc715a671c8176a1e476482fb75066aff0074772a0b4e339e8fc23e4c7d4821700f2bd3fd2fb0505dd02246fe56df419ecef74f552bf7e49091ce551d3834db96254eed81ced477d2164176e0e1df5067920f0ddca826f14c3699986c1511b373790c0ff95ac2ea8e7d1d95cdc4356f948d21dcc83e78e0f6d67fa3f60209cd1ff26b7ce72078f79364e56fda0a0ac50c0c96ae28df9b5c6c76a0b44782e1f148d91b19bb1c0b73df05077dfa50240900826914204885b5067b8f4fbb62d52eda1aed41c8dc951eb18a6310a1f974cc3cfbfd6827744e7d6a2962f8a7e5c41db3d2104469453ee589ba402cd9dbaff4b615076e095a22c16b45fc2a7bb99c5ae3c1f48415d82332cf1c636c8f0d1d282f9894a29b0c9c8c8471eac7f6420a7e0b0ee98940c3b33279813d6a8e27eb472fca89db78da506870c8248a3907fb8d0ee950676ef74772a0b4e2fe4087cd8fa9042e03e5683e97d8282ec838f149772c3ea1fb0ead872bb24152c222dd31185bb7327a012a8e421d1ca786277a5a505fa2904914720f7e03ba541407fba3b9507554e253d443144406c71002c3840b2a25347a88b6f52fd846ac63aca08fc6fca937d1fb2104de027dae6f9c503c70fb5d272b7ed0505629e730cec94004b0dc02a89ca0c6aa2a6ad90ba2600fbddc2fbc09ec4137b38d408a0481a09a45240911f39a511432487646d2ee8419993af292e36d7766e3c673282c3a493d14d0d3ee1332474275755a6e40207a951cfa2b3eae20e8ef94d1dadf2867eb4129a5706bd1c73819c4edbf25ff00d8282a91ce638278b7a668d6fa255127a354fba624d79cc40d2fe7d83ad04be944dab45145f19267c6d6fde5043e8ecb0455ae92691b18119d52ecb32838f13dc8e215063787c6e7eb070e02a0b568f4fba6191f0c44b0f320a63fdd1dca82d18b1f6821f363ea410d80f9560fa5f60a0badd041e934f6a48a3f8c7df99bfdd5115804904556f92591b1eac6750bb2cd072625b9f7fd418dc1d1b9fac08e02a0b3e0536be1d10da6325879bfba0a83fc67f2a095c430a8a2a18aa62da5ad3283f286d40b00a9747582224ea4b7163c283e38d794e6fa1f64209ac07c9e3ce283d639e4e9395bf6820acd24226a98e226c1e6d75458a93068a9ea193091ced4bd81e30476a09350240d008265149109044e91cfb9e13370c9660e73ea4149a6a69aa671144359e6e467641db360389c313a59226eab3373b581b71a0e6c3a7dc6be9e6de6bc5cf11dbe8282f95f077c524f0fc369b7366106739f3ec238ff00baa2dba2d06a52cd3dbdd1f607e4b47de823f4a26bd6c717c4b33e539fa9047d26175b551ee90c61cc0ed5db641f3aca1a9a42d6ccdb6b03aa2f7d9fdd04d68acd9d443c8f1d47ad057a4f747f2f6a82cf8b79022f363ea41018754b296b239de096b2f7038c5bb5058e9f4869a69e389b1480bdd604d9046692c9ad58c8fe2a31970172a386970bada9617c2c0e6eb5b33641f3aba2a9a67344cdb176ccefb3fba097d1a9bc29e2e478ea3d68205fe3bf97b504b57e2ac96862a68c1c9addd5c7e48d883e182c2e7e2119b1b47e112a058d794e7fa1f602a3ab0dc5e1a6a7113d8e71b937083a2babe3abc2e77318e6ea3da33e50a084a499b0d4c729170c37b041608b1da79256462290191c1a0db84a0954020100826102408941138de1b515f1c4c8ded60638b9dad7dbbdb1072e0d80cd4552e9a57b5fe06ab756f95fa104d4d189619233b246969e74153fcd5abf8e8ff8bd482d7087b628c3c82f6b46b9e136ed4159aad199df532be39636b1ee2e6b73b8b9bd904f50520a6a38a0f802ce3ce821310d1faba9ac96a3758fc375c037d882570ca234944d809bb8125ce1c2507c31ac35f5b1c5a8e6b5f193e35f3baa38f0bc12a692adb2ba5616d887017df41c8fd18ab2f27768f3e5504bd6e1f24f86b299ae01ed0d1adbde08b20863a335798dda31d3ea41f6a4d1fa986a6194cb1b846f04817be481d7605553d54b36eac01e7c106fb02094c3a93bda9190deee19b9c37c941f0c630f7564718639ad7b0e57baa3930dc1ea292a84a64616d887017dfde41caed1da9d7277660bf2a06cd1b96fe14ed037cb733d8826692860a48f56269cfc679f18a822ebf069aa2aa495b23035f6b03b7216ec54729d1da93feeb0f4a0eb8f0799b43353ebb2f2b9ae073de50727e6fd4fc6b3d3ea41f4a7c12a23a88a43246446e0e3b77904fa0680402097281204502408a044dd02fc6fa04501ce815d02409022812057e0408a0574020450240902bdb8d02d6405d022816c40ae8040903e7409034020104aa00a04812048120450240140902409024090240900812048041e5c50794020102408a0481a01008040201008254a0481140902409008114090240902408940ae81201024090083cb8a0f280402048120100804020100804020104a204502409022502ba048120481202e811283ca0100812044a057408941e50081204804020100804020100804020107ffd9);