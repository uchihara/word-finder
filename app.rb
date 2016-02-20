require 'sinatra'
require 'sinatra/reloader'
require_relative 'lib/word_finder'

set :erb, :trim => '-'

get '/' do
  texts = (params['texts'] || '').downcase.gsub(/[ \r]/, "").gsub(/\n+/, "\n")
  matrix = texts.split(/\n/).map{|row|row.split(//)}
  lengths = (params['lengths'] || []).map(&:to_i)
  nouse_dict = params['nouse_dict']=='1'
  if texts && !lengths.empty?
    finder = WordFinder.new matrix, lengths, !nouse_dict
    results = finder.find_words
  else
    results = []
  end
  slim :index, locals: { texts: texts, lengths: lengths, nouse_dict: nouse_dict, results: results }
end
